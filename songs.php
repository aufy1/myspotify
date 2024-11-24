<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

require_once 'config.php';
?>

<?php require_once 'head.php'; ?>	
<body class="bg-gray-800 text-white">
<?php require_once 'header.php'; ?>	
<main class="py-10">
    <div class="container mx-auto">
        <!-- Button to Add Song -->
        <!-- Section for My Songs and Add Song Button -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Moje Piosenki</h2>

            <!-- Button to Add Song -->
            <button id="addSongButton" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Dodaj Piosenkę
            </button>
        </div>

        <!-- Form to Add Song (Initially Hidden) -->
        <div id="addSongForm" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-50">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3">
                <h2 class="text-xl font-bold mb-4">Dodaj nową piosenkę</h2>
                <form id="addSongFormElement" enctype="multipart/form-data">
                    <!-- Form Fields -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium mb-2">Tytuł:</label>
                        <input type="text" id="title" name="title" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-green-500" required>
                    </div>

                    <div class="mb-4">
                        <label for="musician" class="block text-sm font-medium mb-2">Wykonawca:</label>
                        <input type="text" id="musician" name="musician" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-green-500" required>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="block text-sm font-medium mb-2">Plik:</label>
                        <input type="file" id="file" name="file" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-green-500" required>
                    </div>

                    <div class="mb-4">
                        <label for="lyrics" class="block text-sm font-medium mb-2">Tekst piosenki:</label>
                        <textarea id="lyrics" name="lyrics" rows="4" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-green-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="musictype" class="block text-sm font-medium mb-2">Rodzaj muzyki:</label>
                        <select id="musictype" name="musictype" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-green-500">
                            <!-- Music Types Options -->
                            <option value="1">Pop</option>
                            <option value="2">Rock</option>
                            <option value="3">Hip-hop</option>
                            <option value="4">Electronic Dance</option>
                            <option value="5">R&B</option>
                            <option value="6">Latin</option>
                            <option value="7">Country</option>
                            <option value="8">Metal</option>
                            <option value="9">Jazz</option>
                            <option value="10">Classic</option>
                            <option value="11">Inny</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" id="cancelButton" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Anuluj
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Dodaj
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="songsContainer" class="flex flex-wrap gap-4">
        <!-- Songs -->
        </div>



        <!-- Section for My Playlists -->
        <div class="flex justify-between items-center mt-10 mb-4">
            <h2 class="text-2xl font-bold">Moje Playlisty</h2>

            <!-- Button to Add Playlist -->
            <button id="addPlaylistButton" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Dodaj Playlistę
            </button>
        </div>

        <!-- Form to Add Playlist (Initially Hidden) -->
        <div id="addPlaylistForm" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-50">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3">
                <h2 class="text-xl font-bold mb-4">Dodaj nową playlistę</h2>
                <form id="addPlaylistFormElement">
                    <div class="mb-4">
                        <label for="playlistName" class="block text-sm font-medium mb-2">Nazwa playlisty:</label>
                        <input type="text" id="playlistName" name="name" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label for="playlistVisibility" class="block text-sm font-medium mb-2">Publiczna:</label>
                        <select id="playlistVisibility" name="public" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-blue-500">
                            <option value="1">Tak</option>
                            <option value="0">Nie</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" id="cancelPlaylistButton" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Anuluj
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Dodaj
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Playlists Container -->
        <div id="playlistsContainer" class="flex flex-wrap gap-4">
            <!-- Playlists -->
        </div>


        <div id="editPlaylistForm" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-50">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3">
        <h2 class="text-xl font-bold mb-4">Edytuj playlistę</h2>
        <form id="editPlaylistFormElement">
        <input type="hidden" id="playlistIdInput" name="playlistId" value="">


            <!-- Public/Private Setting -->
            <div class="mb-4">
                <label for="playlistPublic" class="block text-sm font-medium mb-2">Ustawienia prywatności:</label>
                <select id="playlistPublic" name="playlistPublic" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-green-500">
                    <option value="1">Publiczna</option>
                    <option value="0">Prywatna</option>
                </select>
            </div>

            <!-- Add Songs -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Dodaj Piosenki:</label>
                <div id="songsCheckboxList" class="space-y-2">
                    <!-- Song checkboxes will be populated dynamically -->
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelEditPlaylistButton" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Anuluj
                </button>
                <button id="submitEditPlaylistButton"  type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Zapisz
                </button>
            </div>
        </form>
    </div>
</div>



    </div>
</main> 

<?php require_once 'footer.php'; ?>            

<script>
// Show the form when the "Add Song" button is clicked
const addSongButton = document.getElementById('addSongButton');
const addSongForm = document.getElementById('addSongForm');
const cancelSongButton = document.getElementById('cancelButton');

addSongButton.addEventListener('click', () => {
    addSongForm.classList.remove('hidden');
});

// Hide the form when the "Cancel" button in Add Song form is clicked
cancelSongButton.addEventListener('click', () => {
    addSongForm.classList.add('hidden');
});

// Show the form when the "Add Playlist" button is clicked
const addPlaylistButton = document.getElementById('addPlaylistButton');
const addPlaylistForm = document.getElementById('addPlaylistForm');
const cancelPlaylistButton = document.getElementById('cancelPlaylistButton');

addPlaylistButton.addEventListener('click', () => {
    addPlaylistForm.classList.remove('hidden');
});

// Hide the form when the "Cancel" button in Add Playlist form is clicked
cancelPlaylistButton.addEventListener('click', () => {
    addPlaylistForm.classList.add('hidden');
});

// Handle the form submission for adding a new playlist
const addPlaylistFormElement = document.getElementById('addPlaylistFormElement');

addPlaylistFormElement.addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent the default form submission
    
    // Prepare the form data to be sent
    const formData = new FormData(this);

    // Send the form data to the server (use your actual server URL)
    fetch('api/spotify/add_playlist.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())  // Parse the response as JSON
    .then(data => {
        if (data.success) {
            // Handle successful playlist addition
            alert('Playlist added successfully!');
            
            // Optionally, update the playlist list (e.g., append the new playlist)
            // Here you would typically call a function to refresh the playlist display
            // Example: updatePlaylistList();

            // Optionally, hide the form after successful submission
            addPlaylistForm.classList.add('hidden');
        } else {
            // Handle error response from server
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the playlist.');
    });
});


    // Handle the form submission
    document.getElementById('addSongFormElement').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const response = await fetch('api/spotify/add_song.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.text();
        console.log(result);

        if (response.ok) {
            try {
                const jsonResult = JSON.parse(result);
                alert(jsonResult.message);
                e.target.reset();
                addSongForm.classList.add('hidden');
                loadSongs();  // Reload songs after adding a new one
            } catch (error) {
                console.error('Error parsing JSON response:', error);
                alert('Błąd przy przetwarzaniu odpowiedzi.');
            }
        } else {
            try {
                const jsonError = JSON.parse(result);
                alert(jsonError.error);
            } catch (error) {
                console.error('Error parsing error response:', error);
                alert('Wystąpił błąd.');
            }
        }
    });

    // Fetch and display the songs for the logged-in user
    document.addEventListener('DOMContentLoaded', async () => {
        loadSongs();
        loadPlaylists();
    });



let currentAudio = null; // Reference to the currently playing audio
let currentPlayButton = null; // Reference to the current play button
let currentProgressBar = null; // Reference to the current progress bar

async function loadSongs() {
    try {
        const response = await fetch('api/spotify/get_songs.php', {
            method: 'GET',
        });
        const result = await response.json();

        if (response.ok) {
            const songsContainer = document.getElementById('songsContainer');
            songsContainer.innerHTML = ''; // Clear existing songs

            result.songs.forEach(song => {
                const songBox = document.createElement('div');
                songBox.classList.add(
                    'w-60', 
                    'h-60', 
                    'bg-gray-700', 
                    'rounded-3xl', 
                    'flex', 
                    'flex-col', 
                    'items-center', 
                    'justify-center', 
                    'shadow-lg', 
                    'relative', 
                    'overflow-hidden', // Ensure content stays within the box
                    'transition-transform', // Enable smooth scaling effect
                    'duration-300', // Duration of the scaling effect
                    'hover:scale-105' // Scale slightly on hover
                );

                songBox.innerHTML = `
                    <div 
                        class="progress-bar w-full bg-gray-600 rounded-t-2xl absolute top-0 left-0 transition-all duration-300 hover:h-2 z-10">
                        <div 
                            class="progress-fill h-full bg-green-400 rounded-t-2xl transition-all duration-300" 
                            style="width: 0%;">
                        </div>
                    </div>

                    <div class="absolute inset-0 -mt-12 flex items-center justify-center">
                        <div class="bg-gray-500 opacity-50 p-6 rounded-full">
                            <img src="media/spotify_icons/music-solid.svg" alt="Music Icon" class="w-12 h-12 text-gray-200" />
                        </div>
                    </div>

                    <div class="absolute bottom-2 left-2 right-2 text-center">
                        <h3 class="text-xl font-semibold text-white">${song.title}</h3>
                        <p class="text-sm text-gray-400">${song.musician}</p>
                    </div>

                    <button 
                        class="playButton absolute bottom-2 right-2 bg-green-600 hover:bg-green-700 text-white rounded-full p-3 flex items-center justify-center" 
                        data-filename="${song.filename}">
                        <img src="media/storage_icons/play-solid.svg" alt="Play Icon" class="playIcon w-6 h-6" />
                    </button>
                `;

                songsContainer.appendChild(songBox);
            });

            // Add event listeners to all play buttons
            document.querySelectorAll('.playButton').forEach(button => {
                button.addEventListener('click', () => togglePlayPause(button));
            });
        } else {
            alert(result.error || 'Wystąpił błąd');
        }
    } catch (error) {
        console.error('Error fetching songs:', error);
        alert('Wystąpił błąd podczas ładowania piosenek.');
    }
}

async function loadPlaylists() {
    try {
        const response = await fetch('api/spotify/get_playlists.php', {
            method: 'GET',
        });
        const result = await response.json();

        if (response.ok) {
            const playlistsContainer = document.getElementById('playlistsContainer');
            playlistsContainer.innerHTML = ''; // Clear existing playlists

            result.playlists.forEach(playlist => {
    const playlistBox = document.createElement('div');
                playlistBox.classList.add(
                    'w-60', 
                    'h-60', 
                    'bg-gray-700', 
                    'rounded', 
                    'flex', 
                    'flex-col', 
                    'items-center', 
                    'justify-center', 
                    'shadow-lg', 
                    'relative', 
                    'overflow-hidden', // Ensure content stays within the box
                    'transition-transform', // Enable smooth scaling effect
                    'duration-300', // Duration of the scaling effect
                    'hover:scale-105' // Scale slightly on hover
                );

                playlistBox.innerHTML = `
                    <div class="absolute inset-0 -mt-12 flex items-center justify-center">
                        <div class="bg-gray-500 opacity-50 p-6 rounded-2xl">
                            <img src="media/spotify_icons/list-solid.svg" alt="Playlist Icon" class="w-12 h-12 text-gray-200" />
                        </div>
                    </div>

                    <div class="absolute bottom-2 left-2 right-2 text-center">
                        <h3 class="text-xl font-semibold text-white">${playlist.name}</h3>
                        <p class="text-sm text-gray-400">${playlist.public == '1' ? 'Publiczna' : 'Prywatna'}</p>
                    </div>

                    <!-- Left button (Gear icon) -->
                    <button 
                        class="viewPlaylistButton absolute bottom-2 left-2 opacity-90 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 flex items-center justify-center" 
                        data-playlist-id="${playlist.id}">
                        <img src="media/spotify_icons/gear-solid.svg" alt="View Playlist Icon" class="viewPlaylistIcon w-6 h-6" />
                    </button>

                    <!-- Right button (Play icon) -->
                    <button 
                        class="playButton absolute bottom-2 right-2 bg-green-600 hover:bg-green-700 text-white rounded-full p-3 flex items-center justify-center" 
                        data-playlist-id="${playlist.id}">
                        <img src="media/storage_icons/play-solid.svg" alt="Play Icon" class="playIcon w-6 h-6" />
                    </button>
                `;

                playlistsContainer.appendChild(playlistBox);
            });


            // Add event listeners to all view buttons
            document.querySelectorAll('.viewPlaylistButton').forEach(button => {
                button.addEventListener('click', () => viewPlaylistDetails(button));
            });
        } else {
            alert(result.error || 'Wystąpił błąd');
        }
    } catch (error) {
        console.error('Error fetching playlists:', error);
        alert('Wystąpił błąd podczas ładowania playlist.');
    }
}

let isFormSubmitting = false;

function viewPlaylistDetails(button) {
    const playlistId = $(button).data('playlist-id');

    const $editPlaylistForm = $('#editPlaylistForm');
    const $editPlaylistFormElement = $('#editPlaylistFormElement');
    const $songsCheckboxList = $('#songsCheckboxList');

    resetEditPlaylistForm();
    $editPlaylistForm.removeClass('hidden');

    $('#playlistIdInput').val(playlistId);
    $('#playlistPublic').val('1');

    async function loadSongs() {
        try {
            const response = await fetch('api/spotify/get_songs.php');
            const result = await response.json();

            if (response.ok) {
                $songsCheckboxList.empty();

                result.songs.forEach(song => {
                    const songCheckbox = $(`
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="song-${song.id}" name="songs[]" value="${song.id}" class="form-checkbox h-5 w-5 text-green-500">
                            <label for="song-${song.id}" class="text-white">${song.title} - ${song.musician}</label>
                        </div>
                    `);
                    $songsCheckboxList.append(songCheckbox);
                });
            } else {
                alert('Błąd podczas ładowania piosenek.');
            }
        } catch (error) {
            alert('Wystąpił błąd podczas ładowania piosenek.');
        }
    }

    loadSongs();

    $('#cancelEditPlaylistButton').on('click', () => {
        resetEditPlaylistForm();
        $editPlaylistForm.addClass('hidden');
    });

    function resetEditPlaylistForm() {
        $editPlaylistFormElement[0].reset();
        $songsCheckboxList.empty();
        $('#playlistIdInput').val('');
    }

    async function handleSubmit(event) {
        event.preventDefault();
        if (isFormSubmitting) return;

        isFormSubmitting = true;

        const formData = new FormData($editPlaylistFormElement[0]);
        const playlistId = $('#playlistIdInput').val();

        formData.append('playlistId', playlistId);

        try {
            const response = await fetch('api/spotify/update_playlist.php', {
                method: 'POST',
                body: formData,
            });
            const result = await response.json();

            if (result.success) {
                resetEditPlaylistForm();
                $editPlaylistForm.addClass('hidden');
            } else {
                alert(`Błąd: ${result.error}`);
            }
        } catch (error) {
            alert('Wystąpił błąd podczas aktualizacji playlisty.');
        } finally {
            isFormSubmitting = false;
        }
    }

    $editPlaylistFormElement.off('submit', handleSubmit);
    $editPlaylistFormElement.on('submit', handleSubmit);
}






function togglePlayPause(button) {
    const filename = $(button).data('filename');
    const playIcon = $(button).find('.playIcon');
    const songBox = $(button).closest('.w-60');
    const progressBar = songBox.find('.progress-bar');
    const progressFill = progressBar.find('.progress-fill');

    if (currentAudio && currentAudio.src.endsWith(filename)) {
        // Jeśli obecny utwór gra, wstrzymaj lub kontynuuj
        if (currentAudio.paused) {
            currentAudio.play();
            playIcon.attr('src', 'media/spotify_icons/pause-solid.svg'); // Zmień ikonę na "pauza"
            progressBar.addClass('active'); // Pokaż pasek postępu
        } else {
            currentAudio.pause();
            playIcon.attr('src', 'media/storage_icons/play-solid.svg'); // Zmień ikonę na "odtwarzanie"
            progressBar.removeClass('active'); // Ukryj pasek postępu
        }
    } else {
        // Jeśli wybrano inny utwór
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;

            if (currentPlayButton) {
                const prevPlayIcon = $(currentPlayButton).find('.playIcon');
                prevPlayIcon.attr('src', 'media/storage_icons/play-solid.svg'); // Resetuj poprzedni przycisk

                if (currentProgressBar) {
                    currentProgressBar.removeClass('active'); // Ukryj poprzedni pasek postępu
                    currentProgressBar.find('.progress-fill').css('width', '0%'); // Resetuj pasek
                }
            }
        }

        // Odtwórz nowy utwór
        currentAudio = new Audio(`uploads/songs/${filename}`);
        currentAudio.play();
        playIcon.attr('src', 'media/spotify_icons/pause-solid.svg'); // Zmień ikonę na "pauza"
        currentPlayButton = button;
        currentProgressBar = progressBar;
        progressBar.addClass('active'); // Pokaż pasek postępu

        // Aktualizuj pasek postępu
        $(currentAudio).on('timeupdate', function() {
            if (currentAudio && currentProgressBar) {
                const progress = (currentAudio.currentTime / currentAudio.duration) * 100;
                currentProgressBar.find('.progress-fill').css('width', `${progress}%`);
            }
        });

        // Resetuj przycisk i pasek po zakończeniu utworu
        $(currentAudio).on('ended', function() {
            playIcon.attr('src', 'media/storage_icons/play-solid.svg'); // Zresetuj do ikony "odtwarzanie"
            currentAudio = null;
            currentPlayButton = null;

            if (currentProgressBar) {
                currentProgressBar.removeClass('active'); // Ukryj pasek postępu
                currentProgressBar.find('.progress-fill').css('width', '0%'); // Resetuj pasek
                currentProgressBar = null;
            }
        });
    }

    // Dodaj nasłuchiwanie kliknięcia na pasek postępu
    progressBar.on('click', function(event) {
        const progressBarWidth = progressBar.width(); // Szerokość paska
        const clickPosition = event.offsetX; // Pozycja kliknięcia względem lewej krawędzi paska
        const newTime = (clickPosition / progressBarWidth) * currentAudio.duration; // Oblicz nowy czas w sekundach

        currentAudio.currentTime = newTime; // Ustaw czas odtwarzania na kliknięte miejsce
    });
}



function updateProgressBar(audio, progressFill) {
    audio.addEventListener('timeupdate', () => {
        const progress = (audio.currentTime / audio.duration) * 100;
        progressFill.style.width = `${progress}%`;
    });
}


</script>
</body>
</html>
