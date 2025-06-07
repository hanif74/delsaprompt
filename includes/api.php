<?php

add_action('rest_api_init', function () {
	register_rest_route('delsaprompt/v1', '/generate', [
		'methods'  => 'POST',
		'callback' => 'delsaprompt_handle_generate',
		'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
	]);
});

function delsaprompt_handle_generate($request) {
	$api_key = get_option('delsaprompt_api_key');

	if (!$api_key) {
		return new WP_REST_Response(['error' => 'OpenAI API key is not set.'], 400);
	}

	$body = $request->get_json_params();
	$prompt = sanitize_text_field($body['prompt'] ?? '');
	$temperature = floatval($body['temperature'] ?? 0.7);
	$max_tokens = intval($body['max_tokens'] ?? 256);
	$top_p = floatval($body['top_p'] ?? 1.0);
    $model = sanitize_text_field($body['model'] ?? 'gpt-3.5-turbo');

    $allowed_models = ['gpt-3.5-turbo', 'gpt-4.1', 'gpt-4.1-mini', 'gpt-4o'];
    if (!in_array($model, $allowed_models, true)) {
        return new WP_REST_Response(['error' => 'Invalid model'], 400);
    }


	if (!$prompt) {
		return new WP_REST_Response(['error' => 'Prompt is required.'], 400);
	}

    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode([
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => $temperature,
            'max_tokens' => $max_tokens,
            'top_p' => $top_p,
        ]),
        'timeout' => 15,
    ]);

	if (is_wp_error($response)) {
		return new WP_REST_Response(['error' => 'Request failed.'], 500);
	}

    $data = json_decode(wp_remote_retrieve_body($response), true);
  
    return new WP_REST_Response([
        'result' => $data['choices'][0]['message']['content'] ?? 'No result',
    ]);
}
