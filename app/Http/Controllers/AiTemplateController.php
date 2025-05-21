<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use Orhanerday\OpenAi\OpenAi;
use App\Models\Utility;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AiTemplateController extends Controller
{
    public function create($moduleName)
    {
        try {
            $templateName = Template::where('module', $moduleName)->get();
            return view('generate', compact('templateName'));
        } catch (Exception $e) {
            return response()->json(['error', __($e->getMessage())]);
        }
    }

    public function getKeywords(Request $request, $id)
    {
        $template = Template::find($id);
        $field_data = json_decode($template->field_json);
        $html = '<div class="row">';
        foreach ($field_data->field as $value) {
            $html .= '<div class="form-group col-md-12">
                         <label class="form-label ">' . $value->label . '</label>';
            if ($value->field_type == "text_box") {

                $html .= '<input type="text" class="form-control" name="' . $value->field_name . '" value="" placeholder="' . $value->placeholder . '" required">';
            }
            if ($value->field_type == "textarea") {
                $html .= '<textarea type="text" rows=3 class="form-control " id="description" name="' . $value->field_name . '" placeholder="' . $value->placeholder . '" required></textarea>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';
        return response()->json(
            [
                'success' => true,
                'template' => $html,
            ]
        );
    }


    // public function AiGenerate(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $post = $request->all();
    //         unset($post['_token'], $post['template_name'], $post['tone'], $post['ai_creativity'], $post['num_of_result'], $post['result_length']);
    //         $data = array();
    //         $key_data = DB::table('settings')->where('name', 'chatgpt_key')->first();
    //         $modelName = DB::table('settings')->where('name','chat_gpt_model')->first();
    //         if ($key_data) {
    //             $open_ai = new OpenAi($key_data->value);
    //         } else {
    //             $data['status'] = 'error';
    //             $data['message'] = __('Please set proper configuration for Api Key');
    //             return $data;
    //         }

    //         $prompt = '';
    //         $model = '';
    //         $text = '';
    //         $ai_token = '';
    //         $counter = 1;
    //         $template = Template::where('id', $request->template_name)->first();

    //         if ($request->template_name) {
    //             $required_field = array();
    //             $data_field = json_decode($template->field_json);
    //             foreach ($data_field->field as  $val) {
    //                 request()->validate([$val->field_name => 'required|string']);
    //             }

    //             $prompt = $template->prompt;
    //             foreach ($data_field->field as  $field) {

    //                 $text_rep = "##" . $field->field_name . "##";
    //                 if (strpos($prompt, $text_rep) !== false) {
    //                     $field->value = $post[$field->field_name];
    //                     $prompt = str_replace($text_rep, $post[$field->field_name], $prompt);
    //                 }
    //                 if ($template->is_tone == 1) {
    //                     $tone = $request->tone;
    //                     $param = "##tone_language##";
    //                     $prompt = str_replace($param, $tone, $prompt);
    //                 }
    //             }
    //         }
    //         $lang_text = "Provide response in " . $request->language . " language.\n\n ";
    //         $ai_token = (int)$request->result_length;

    //         $max_results = (int)$request->num_of_result;
    //         $ai_creativity = (float)$request->ai_creativity;
    //         $complete = $open_ai->completion([
    //             'model' => $modelName ? $modelName->value : '',
    //             'prompt' => $prompt . ' ' . $lang_text,
    //             'temperature' => $ai_creativity,
    //             'max_tokens' => $ai_token,
    //             'n' => $max_results
    //         ]);
    //         $response = json_decode($complete, true);
    //         if (isset($response['choices'])) {
    //             if (count($response['choices']) > 1) {
    //                 foreach ($response['choices'] as $value) {
    //                     $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
    //                     $counter++;
    //                 }
    //             } else {
    //                 $text = $response['choices'][0]['text'];
    //             }

    //             $tokens = $response['usage']['completion_tokens'];
    //             $data = trim($text);
    //             return $data;
    //         } else {
    //             $data['status'] = 'error';
    //             $data['message'] = __('Text was not generated, please try again');
    //             return $data;
    //         }
    //     }
    // }

    public function AiGenerate(Request $request)
    {
        if ($request->ajax()) {
            try {
                $post = $request->except(['_token', 'template_name', 'tone', 'ai_creativity', 'num_of_result', 'result_length']);

                $key_data = DB::table('settings')->where('name', 'chatgpt_key')->first();
                $modelName = DB::table('settings')->where('name', 'chat_gpt_model')->first();

                if (!$key_data) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('Please set proper configuration for API Key')
                    ]);
                }

                try {
                    $open_ai = new OpenAi($key_data->value);
                } catch (Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to initialize OpenAI client: ' . $e->getMessage()
                    ]);
                }

                $template = Template::find($request->template_name);
                if (!$template) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('Template not found.')
                    ]);
                }

                // Dynamic validation
                $data_field = json_decode($template->field_json);
                $rules = [];
                foreach ($data_field->field as $val) {
                    $rules[$val->field_name] = 'required|string';
                }

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed.',
                        'errors' => $validator->errors()
                    ], 422);
                }

                // Prepare prompt
                $prompt = $template->prompt;
                foreach ($data_field->field as $field) {
                    $text_rep = "##" . $field->field_name . "##";
                    if (strpos($prompt, $text_rep) !== false) {
                        $prompt = str_replace($text_rep, $post[$field->field_name], $prompt);
                    }
                }

                if ($template->is_tone == 1 && $request->tone) {
                    $prompt = str_replace("##tone_language##", $request->tone, $prompt);
                }

                $lang_text = "Provide response in " . $request->language . " language.\n\n ";
                $full_prompt = $prompt . ' ' . $lang_text;

                $ai_token = (int) $request->result_length;
                $max_results = (int) $request->num_of_result;
                $ai_creativity = (float) $request->ai_creativity;

                $complete = $open_ai->completion([
                    'model' => $modelName ? $modelName->value : '',
                    'prompt' => $full_prompt,
                    'temperature' => $ai_creativity,
                    'max_tokens' => $ai_token,
                    'n' => $max_results
                ]);

                $response = json_decode($complete, true);

                // Handle OpenAI error
                if (isset($response['error'])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'OpenAI Error: ' . $response['error']['message']
                    ], 400);
                }

                // Process response
                if (isset($response['choices'])) {
                    $text = '';
                    $counter = 1;
                    if (count($response['choices']) > 1) {
                        foreach ($response['choices'] as $value) {
                            $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
                            $counter++;
                        }
                    } else {
                        $text = $response['choices'][0]['text'];
                    }
                    return response()->json([
                        'status' => 'success',
                        'data' => trim($text)
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('Text was not generated, please try again.')
                    ]);
                }

            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Server Error: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function grammar($moduleName)
    {
        $templateName = Template::where('module', $moduleName)->first();
        return view('template.grammar_ai', compact('templateName'));
    }

    // public function grammarProcess(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $post = $request->all();
    //         unset($post['_token'], $post['template_name'], $post['tone'], $post['ai_creativity'], $post['num_of_result'], $post['result_length']);
    //         $data = array();
    //         $key_data = DB::table('settings')->where('name', 'chatgpt_key')->first();
    //         $modelName = DB::table('settings')->where('name', 'chat_gpt_model')->first();


    //         if ($key_data) {
    //             $open_ai = new OpenAi($key_data->value);
    //         } else {
    //             $data['status'] = 'error';
    //             $data['message'] = __('Please set proper configuration for Api Key');
    //             return $data;
    //         }

    //         $counter = 1;
    //         $prompt = "please correct grammar mistakes and spelling mistakes in this: . $request->description .";
    //         $is_tone = 1;
    //         $ai_token = strlen($request->description);
    //         $max_results = 1;
    //         $ai_creativity = 1.0;
    //         $complete = $open_ai->completion([
    //             'model' => $modelName ? $modelName->value : '',
    //             'prompt' => $prompt,
    //             'temperature' => $ai_creativity,
    //             'max_tokens' => $ai_token,
    //             'n' => $max_results
    //         ]);
    //         $response = json_decode($complete, true);
    //         if (isset($response['choices'])) {
    //             if (count($response['choices']) > 1) {
    //                 foreach ($response['choices'] as $value) {
    //                     $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
    //                     $counter++;
    //                 }
    //             } else {
    //                 $text = $response['choices'][0]['text'];
    //             }
    //             $tokens = $response['usage']['completion_tokens'];
    //             $data = trim($text);
    //             return $data;
    //         } else {
    //             $data['status'] = 'error';
    //             $data['message'] = __('Text was not generated, due to invalid API key');
    //             return $data;
    //         }
    //     }
    // }

    public function grammarProcess(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => __('Invalid request.')
            ]);
        }

        try {
            $description = trim($request->description);

            if (empty($description)) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Please Enter The Description.')
                ]);
            }

            $key_data = DB::table('settings')->where('name', 'chatgpt_key')->first();
            $modelName = DB::table('settings')->where('name', 'chat_gpt_model')->first();

            if (!$key_data || empty($key_data->value)) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Invalid or missing OpenAI API key.')
                ]);
            }

            $open_ai = new OpenAi($key_data->value);
            $prompt = "Please correct grammar and spelling mistakes in this: " . $description;

            $response = $open_ai->completion([
                'model' => $modelName->value ?? 'text-davinci-003',
                'prompt' => $prompt,
                'temperature' => 1.0,
                'max_tokens' => max(strlen($description), 100),
                'n' => 1
            ]);

            $response = json_decode($response, true);

            if (isset($response['choices'][0]['text'])) {
                return response()->json([
                    'status' => 'success',
                    'result' => trim($response['choices'][0]['text']),
                ]);
            }

            if (isset($response['error'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OpenAI Error: ' . $response['error']['message']
                ]);
            }

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'Incorrect API key') || str_contains($errorMessage, '401')) {
                $errorMessage = __('Invalid or expired OpenAI API key.');
            }

            return response()->json([
                'status' => 'error',
                'message' => $errorMessage
            ]);
        }
    }

}
