<?php

if ($argc < 2) {
    die("Gimme parameter - 1 or 2");
}

$state = "23948u2348usldkfgjhlsdkfjalskdjflaksjdf1"; //random string?

//has to persist between phase 1 and 2

if ($argv[1] == 1) {
    //set blah = "blah"

    //$cookie_header = 'snipeitv6_session=IguKWh5LyfeAD67p4D27KRH7jKV1x3JcJnmVGBFb; XSRF-TOKEN=eyJpdiI6ImVvS28zQ1REcWgyMCtzM2V2L1JybUE9PSIsInZhbHVlIjoiWURSK2grWmlIYVozbXIvNzlySWtEellFWFpwaG52T3Mzdzg4KytGMVNKK2ZLdkhPRk03UnNqZkt1WGxFVXZodEtMREE1d09CcDVNRzE1bXN0dVhnQ3g5K0VoUFVDS2FUQnZYcW5WSjNQa3VmRVc5ZUlzcTZ1T2JuRTk2L1REUXEiLCJtYWMiOiI4YTliMTkwNWJlNWEwOTYxODcwNDliZThkODM1YTE1YTBjNmE3NDc1MmE3MGE0ZjAyMGQ2MjBmMTMzNmMwOTNlIiwidGFnIjoiIn0%3D; snipeitv6_session=IguKWh5LyfeAD67p4D27KRH7jKV1x3JcJnmVGBFb; snipeit_passport_token=eyJpdiI6ImwyZ3lmZUhaY1BOVXlEU2VoZ2VBZkE9PSIsInZhbHVlIjoiL1Y2bWkxTENubzhtVl…kZTaGZQcXdkRkkvUWRadlgxT2pBRFFjbnNLL3M5Z29YaUJmQkFLd1hWdUZGYUlXZVdaS1RGWEY2NUFkMGxtV2RXZlQrV2VOOUZ4TjM4c2NYN0l3TmlSeVdmcWsyV0F1U2NlTzRJMGlZclhxMFVQS3R4WE1FS1R1WVVoTzIvay93UW9FSHVEQS95dkhqM2tMWmM4ZTU4VUhUYk5LVkpybGM2TTZGR1BGM0JGdDFBTEh5a0NhOUZZRFJBZUJzZGVhQWdTYlg2c1dJei9aaTc0VmlZaUs5VDZGZzllbHN4a3Z3VEZlRzVtSndCWEQxSnBGUjJZQmRWRTlaN3MyTWt0emlOM0pOQVJQbUgyV29JcjNrcloiLCJtYWMiOiIyNTY5YmU4OTlmMDNhN2RjNjY0N2Y1YzJmOThkYTI5NjczYzc1OWIyZTBjZWIyMTMzYTIxMjNiMjUwYjgzMGMyIiwidGFnIjoiIn0%3D; optional_user_info_open=true';
    //$cookie_header =
    $cookie_header[] = 'snipeit_passport_token=eyJpdiI6ImswZ0F2L1J3Rk50eEJZRDg5cTgrb0E9PSIsInZhbHVlIjoiWXNzeXU1RmlLOGZmdnlhNUtDeVVDRE9iT0hjcDQ3bVN6MEVMb1N2VmxwaWZDU2RrUkRXdTRLRjMyblk3eUlUcW92bXFXSDk4TXY5SnBZU0tzenpjSUZlcVRsd2RIV1k3MG9xNlZqQXN0Ymc3TGpjdTBRWnFxUFY5VzZxNkhXczMxUTRQdFJ1RzNlcUY5a1d4L2hUMUEvYS9WWE16SnNtOGN0Vkw1bHRJcGdXNncwMHQwa1Ria2UwbUdsRnRTUE9TQXdxYytNK21BS1JHTTFrVm01NGdESEMxR1YwbkpXTE93Q3VCMDZQTmZVYXZjNG5lOXduVjRJUWJVV3NPenUrdVdSUjMyd2d5cWVwU0NiOEpGMXgrandnMURHUmhJeUF6Mk02WW80TGNueHRZN2RHb0hpQ0xGbEZ5bEE5Z1lDWEoiLCJtYWMiOiJmYTBjNmM2NGIwODEwMTYzYWU2NTkwMGI5NWFhZDQxMDc3NWVlMzI0NjBhMGFiNWM3NGZhMjAxYzEyMTNmNzI5IiwidGFnIjoiIn0%3D';
    $cookie_header[] = 'XSRF-TOKEN=eyJpdiI6IlhFRE5wa0lMTHZndWlEQ09EZXVFUUE9PSIsInZhbHVlIjoiRXJtSEFOaHQ0OGh1VTdSeUZJNjR3RWxWVFhFWWNWQUp1emVncVBrWlc0aHlhdDM5R2VOc0VKK0VuZnNKYzVvV0tRQ2E0cjdvVUJoM1cvb1hwN0Y0QjU4UGNUMGd3SG1xS3E1eEg2NkNMMERuUkZpWU16VzRPODB2R0VSanI2KzYiLCJtYWMiOiIwZjM5NjYzOWU2ZGQ1NGZkZDcwMjMxN2NkMmUxMjZjOTlkMjdmYjg4M2M5ZTY4MzQ2MzUxNDEzZTZlNDhlZDU3IiwidGFnIjoiIn0%3D';
    $cookie_header[] = 'snipeit_session=vAKbo0ujC9UvU23ZSbSgZaEunyftBEPj5TjVWtmK';

    $cookie_header = join("; ", $cookie_header);

    $output = `curl --cookie-jar cookiefile --cookie "$cookie_header" https://snipe.ngrok.dev/oauth/clients`;
    print $output."\n";

    $ans = json_decode($output);
    print_r($ans);
    //exit(0); // is this cookie jar even helping us? Yes, it's good at _this_ point?
    if (!$ans) {
        print "Bad cookie header I guess?\n";
        //exit(0);
    }

    // first, we get the 'client' I guess?
    /*
    $post = [
            "name" => "my client",
            "confidential" => true, //maybe true is better than false here? I dunno
            "redirect" => "http://my-app-thing.com/redirect"
    ];
    $encoded = json_encode($post);
    $create_results = `curl -D - -vvv -b cookiefile --cookie-jar cookiefile --header "Content-Type: application/json" --data '$encoded' http://snipe-it.test/oauth/clients/`;
    print $create_results."\n";
    */

    //okay, so let's skip the client for now - we ought to be able to try to do an auth thingee, right?
    $codeVerifier = preg_replace(["/\//", "/\+/", "/=/"], "", base64_encode(random_bytes(43)));
    file_put_contents("/tmp/code_verifier.txt", $codeVerifier);
    print("CODE VERIFIER IS: $codeVerifier\n");

    $encoded = base64_encode(hash('sha256', $codeVerifier, true));

    $codeChallenge = strtr(rtrim($encoded, '='), '+/', '-_');

    $params = http_build_query([
        'client_id'    => 34,//'5', //integer five? something else?
        //'redirect_uri' => 'https://third-party-app.com/callback',
        //'redirect_uri' => 'http://snipe-it.test/something/something',
        'redirect_uri' => 'com.grokability.snipeitmobile://home', // *MUST* MATCH client_id 5!!!! There is weird logic around 'localhost'
        'response_type'         => 'code',
        //'scope' => 'user:read orders:create',
        //'scope'
        'state'                 => $state,
        'code_challenge'        => $codeChallenge,
        'code_challenge_method' => 'S256',
        // 'prompt' => '', // "none", "consent", or "login"
        //        'prompt' => 'login' //if you make the oauth/authorize call as a non-logged-in user, this needs to be 'login'
        // but *DO NOTE* - setting it to 'login' will BLOW OUT YOUR CURRENT SESSION! It just knocked me out of mine, whoops :/
        // but we aren't - we are *already* logged in (allegedly?)
        //        'prompt' => 'none' // what does *consent* mean? Maybe that's a thing where you say "hey, this thing is going to read your shit, you ok with that?!"
        // okay, 'prompt' => 'none' *only* works if your client "skips authorization" *AND* you already have a valid token. So maybe not what we want here.
        'prompt'                => 'consent'
    ]);

    $results = `curl -b cookiefile --cookie-jar cookiefile 'https://snipe.ngrok.dev/oauth/authorize?$params'`;

    //print $results; //grab these results, paste them in to snipe-it/public/authorization.html, and view that in a logged-in browser. Click 'accept'
    file_put_contents("/Users/spencer/dev/snipe-it/public/authorization.html", $results);

    //and after that, now what? We've 'consented' (I guess?) - now we need to exchange our token for an API key?

    print "NOW GO TO /authorization.html PLEASE! AND PUT YOUR RESULTS IN THE NEXT PHASE.\n";
} elseif ($argv[1] == 2) {
    $result_uri = "code=def50200a632934c99e8aae84a400e29ba3aecc8095783dd4fa52250692c9441f22a379dc3f5faaf868faf95002240f0c3389dc0a5107959719680f035bfa7ac1923064d7b217349c248f88b7ff1c50da680ec97c46b0e6e5ec29692606184e745428ba9281aa04b97ff6f91e36457047cd6d3a393bb8cdcf9e77b90a2484d9a60d574fa51cddd3824f832531d89b03fe1c1d8e639c73c9df11eeb9270a63bf3d9d1c063832e92201165eae6ddecd8ec163ca72992a0ca6985d52f118041f2f3ad77311e002eeae32dc48525d9c37da8e710f0218daabb8c9ba656820c617be951c72fcd72113333755f3b02e4f704ace7842122e0a044ef0b250231af757ab23835e61ad1eddd86a521cb82273431d39bc1a457ce3516a9fedb2826a59acbc66cc36be83454f47f3398a44f452ceb05cebffb456db14831d5ffcaf1c8cea64ad167218c20c276b93961fe4ce6b5f173d5b362a3fcf22eead5a8f07bd6f37a8491543b4f1342f522be01355456c2c9fe4a53da95836588bd1c660e7475181e5be1404d3b2e7c7469bad17125f07235d688&state=23948u2348usldkfgjhlsdkfjalskdjflaksjdf1"; //PASTE THE REDIRECT URL parameter bits YOU GET HERE!

    $urlbits = [];
    parse_str($result_uri, $urlbits);
    if ($urlbits['state'] != $state) {
        die("State doesn't match!!!!!\n");
    }
    print "State matches! Good news!\n";
    $codeVerifier = file_get_contents("/tmp/code_verifier.txt");

    $parameters = [
        'grant_type'    => 'authorization_code',
        'client_id'    => '34',
        'redirect_uri' => 'com.grokability.snipeitmobile://home',
        'code_verifier' => $codeVerifier,
        'code'          => $urlbits['code'],
    ];

    $params = json_encode($parameters);

    // NOTE - no headers this time!
    $results = `curl --json '$params' https://snipe.ngrok.dev/oauth/token`;
    print ($results);
    $parsed_results = json_decode($results);

    print_r($parsed_results);
} else {
    print("Bad phase - want one or two");
}