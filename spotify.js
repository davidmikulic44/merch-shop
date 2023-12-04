const APIController = (function() {
    const clientId = '4510188a46c34c11877d9facbebdc055';
    const clientSecret = 'e6c1fd85453d4db09378db3f3a3a2f6d';

    const _getToken = async () => {
        try {
            const result = await fetch('https://accounts.spotify.com/api/token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'Basic ' + btoa(clientId + ':' + clientSecret)
                },
                body: 'grant_type=client_credentials'
            });

            const data = await result.json();

            if (result.ok) {
                return data.access_token;
            } else {
                return null;
            }
        } catch (error) {
            return null;
        }
    }

    const _getPlaylistTracks = async (token, playlistId) => {
        const result = await fetch(`https://api.spotify.com/v1/playlists/${playlistId}/tracks`, {
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (result.ok) {
            const data = await result.json();
            return data.items;
        } else {
            return null;
        }
    }

    const _getRandomTrack = (tracks) => {
        const randomIndex = Math.floor(Math.random() * tracks.length);
        const randomTrack = tracks[randomIndex].track;
        const imageUrl = randomTrack.album.images[0].url;
        const artistsLength = randomTrack.artists.length;
        let artists = "";

        if(artistsLength != 1) {
            for(let i = 0; i < artistsLength; i++) {
                let artist = randomTrack.artists[i].name;
                if(i == 0) {
                    artists = artist;
                } else {
                    artists = artists + ", " + artist;
                }
            }
        } else {
            artists = randomTrack.artists[0].name;
        }

        const name = randomTrack.name;
    
        return {
            track: randomTrack,
            artists: artists,
            name: name,
            imageUrl: imageUrl,
        };
    }
    
    return {
        async _getToken() {
            const token = await _getToken();
            return token;
        },

        async _getRandomTrackFromPlaylist(playlistId) {
            const token = await this._getToken();

            if (token) {
                const playlistTracks = await _getPlaylistTracks(token, playlistId);

                if (playlistTracks) {
                    const randomTrack = _getRandomTrack(playlistTracks);
                    return randomTrack;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        },
    }
})();

const spotifyImage = document.querySelector('.spotify-image');
const spotifyTitle = document.querySelector('.spotify-title');
const spotifyDescription = document.querySelector('.spotify-description');
const spotifyLink = document.querySelector('.spotify-link');

const updateSpotifyInfo = async () => {
    const trackInfo = await APIController._getRandomTrackFromPlaylist('02MUGO8GB2FAgqIjoII3qc');

    if (trackInfo) {
        spotifyImage.src = trackInfo.imageUrl;
        spotifyTitle.textContent = trackInfo.name;
        spotifyDescription.textContent = `${trackInfo.artists}`;

        spotifyLink.addEventListener('click', () => {
            const trackUrl = trackInfo.track.external_urls?.spotify;
            if (trackUrl) {
                window.open(trackUrl, '_blank');
            } else {
                console.error('No Spotify link found in track information.');
            }
        });
    } else {
        console.error('Failed to get track information.');
    }
};

window.onload = updateSpotifyInfo;
