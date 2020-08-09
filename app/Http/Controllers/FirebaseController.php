<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use \Kreait\Firebase\Auth;
use Kreait\Laravel\Firebase\Facades\FirebaseAuth;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;

class FirebaseController extends Controller
{
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    public function index()
    {
        $auth = app('firebase.auth');
        $uid = 'signing in with Email';

        $customToken = $auth->createCustomToken($uid);
        $customTokenString = (string) $customToken;
        $signInResult = $auth->signInWithCustomToken($customTokenString);
        //$signInResult->firebaseUserId(); to get the user_id of the user




        //$signInResult = $auth->signInWithEmailAndPassword('tauseefanwr@gmail.com', 'custom_password');
        var_dump($signInResult);
        die;

        //verify token
        try {
            $verifiedIdToken = $auth->verifyIdToken($customTokenString);
        } catch (\InvalidArgumentException $e) {
            echo 'The token could not be parsed: ' . $e->getMessage();
        } catch (InvalidToken $e) {
            echo 'The token is invalid: ' . $e->getMessage();
        }

        $uid = $verifiedIdToken->getClaim('sub');
        $user = $auth->getUser($uid);
    }
    public function fireBaseUserList()
    {
        $auth = app('firebase.auth');
        $user = $auth->getUser('signing in with Email');
        //$users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
        var_dump($user);
    }
    public function fireBaseCreateUser()
    {
        $userProperties = [
            'email' => 'tauseefanwar@gmail.com',
            'emailVerified' => false,
            'phoneNumber' => '+918586896868',
            'password' => 'tauseefanwar@123',
            'displayName' => 'Tauseef Anwar',
            'photoUrl' => '',
            'disabled' => false,
        ];
        //$auth = app('firebase.auth');
        $email_exists = $this->checkUserExists($userProperties);

        var_dump($email_exists);
        die;
        //$createdUser = $auth->createUser($userProperties);
    }
    public function checkUserExists($userProperties)
    {
        try {

            $email_exists = $this->auth->getUserByEmail($userProperties['email']);
            if (sizeof($email_exists) >= 1) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            die;
        }
    }
	/*public function fireBaseUserCreation(Request $request)
    {

        // Launch Firebase Auth
        $auth = app('firebase.auth');
        // Retrieve the Firebase credential's token
        $idTokenString = 'asdfasdf57445.3r531348354etrw5etrw34ger3t43wraf.q34rwf43fef34'; //$request->input('Firebasetoken');


        try { // Try to verify the Firebase credential token with Google

            $verifiedIdToken = $auth->verifyIdToken($idTokenString);
            var_dump($verifiedIdToken);
        } catch (\InvalidArgumentException $e) { // If the token has the wrong format

            return response()->json([
                'message' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage()
            ], 401);
        } catch (InvalidToken $e) { // If the token is invalid (expired ...)

            return response()->json([
                'message' => 'Unauthorized - Token is invalide: ' . $e->getMessage()
            ], 401);
        }

        // Retrieve the UID (User ID) from the verified Firebase credential's token
        $uid = $verifiedIdToken->getClaim('sub');

        // Retrieve the user model linked with the Firebase UID
        $user = User::where('firebaseUID', $uid)->first();

        // Here you could check if the user model exist and if not create it
        // For simplicity we will ignore this step

        // Once we got a valid user model
        // Create a Personnal Access Token
        $tokenResult = $user->createToken('Personal Access Token');

        // Store the created token
        $token = $tokenResult->token;

        // Add a expiration date to the token
        $token->expires_at = Carbon::now()->addWeeks(1);

        // Save the token to the user
        $token->save();

        // Return a JSON object containing the token datas
        // You may format this object to suit your needs
        return response()->json([
            'id' => $user->id,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }*/
}
