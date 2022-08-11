<?php

namespace App\Traits;

trait Messages
{

    /**
     * ======================================
     *
     * Error Response
     *
     * ======================================
     */

    /**
     * @param null $fields
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorInvalidParameters($fields = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid parameters.',
            'data' => $fields
        ], 422);
    }

    /**
     * @param null $fields
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorRequiredFieldsNotFilled($fields = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Required fields was not filled.',
            'data' => $fields
        ], 422);
    }

    /**
     * @param null $fields
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorDataValidation($fields = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'The given data was invalid.',
            'data' => $fields
        ], 422);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorInvalidCredentials()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials.',
            'data' => null
        ], 401);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorResourceNotFound()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Resource not found.',
            'data' => null
        ], 404);
    }

    /**
     * @param null $duplicate_value
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorDataAlreadyExists($duplicate_value = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Resource already exists.',
            'data' => $duplicate_value
        ], 409);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorUnauthorizedAccess()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized access.',
            'data' => null
        ], 403);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorUnauthenticated()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthenticated.',
            'data' => null
        ], 401);
    }


    /**
     * ======================================
     *
     * Success Responses
     *
     * ======================================
     */

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonSuccessLogout()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Logout successfully.',
            'data' => null
        ], 200);
    }

    /**
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonSuccessLogin($data = null)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Login successfully.',
            'data' => $data,
        ], 200);
    }

    /**
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonCreateSuccessResponse($data = null)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Created successfully.',
            'data' => $data,
        ], 200);
    }

    /**
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonUpdateSuccessResponse($data = null)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully.',
            'data' => $data,
        ], 200);
    }

    /**
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonDeleteSuccessResponse($data = null)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully.',
            'data' => $data,
        ], 200);
    }


    /**
     * ======================================
     *
     * General Response
     *
     * ======================================
     */

    /**
     * @param $data
     * @param $code
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonSuccessResponse($data, $code, $message = null)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param $data
     * @param $code
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonFailedResponse($data, $code, $message = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }


    /**
     * ======================================
     *
     * FILE DOWNLOAD
     *
     * ======================================
     */

    /**
     * @param $decrypted_file
     * @param $code
     * @param $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function fileDownload($decrypted_file, $code, $headers)
    {
        return response()->stream(
            $this->downloadCallback($decrypted_file), $code, [
                'Content-type' => $headers['content_type'],
                'Content-Disposition' => 'attachment; filename='. $headers['filename'],
            ]
        );
    }

    /**
     * @param $decrypted_file
     * @return \Closure
     */
    private function downloadCallback($decrypted_file)
    {
        return function() use ($decrypted_file) {
            echo $decrypted_file;
        };
    }
}