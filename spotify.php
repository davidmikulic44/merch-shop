<?php

// Include your keys.php file
require_once('./keys.php');

class APIController {
    private static function getToken() {
        try {
            $clientId = getClientId();
            $clientSecret = getClientSecret();

            $result = file_get_contents('https://accounts.spotify.com/api/token', false, stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
                                'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret),
                    'content' => 'grant_type=client_credentials',
                ],
            ]));

            $data = json_decode($result, true);

            if ($data && isset($data['access_token'])) {
                return $data['access_token'];
            } else {
                return null;
            }
        } catch (Exception $error) {
            return null;
        }
    }

    private static function getPlaylistTracks($token, $playlistId) {
        $result = file_get_contents("https://api.spotify.com/v1/playlists/{$playlistId}/tracks", false, stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer {$token}",
            ],
        ]));

        if ($result) {
            $data = json_decode($result, true);
            return isset($data['items']) ? $data['items'] : null;
        } else {
            return null;
        }
    }

    private static function getRandomTrack($tracks) {
        $randomIndex = mt_rand(0, count($tracks) - 1);
        $randomTrack = $tracks[$randomIndex]['track'];
        $imageUrl = $randomTrack['album']['images'][0]['url'];
        $artistsLength = count($randomTrack['artists']);
        $artists = '';

        if ($artistsLength !== 1) {
            foreach ($randomTrack['artists'] as $index => $artist) {
                $artists .= ($index === 0) ? $artist['name'] : ", {$artist['name']}";
            }
        } else {
            $artists = $randomTrack['artists'][0]['name'];
        }

        $name = $randomTrack['name'];

        return [
            'track' => $randomTrack,
            'artists' => $artists,
            'name' => $name,
            'imageUrl' => $imageUrl,
        ];
    }

    public static function getRandomTrackFromPlaylist($playlistId) {
        $token = self::getToken();

        if ($token) {
            $playlistTracks = self::getPlaylistTracks($token, $playlistId);

            if ($playlistTracks) {
                $randomTrack = self::getRandomTrack($playlistTracks);
                return $randomTrack;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
?>
