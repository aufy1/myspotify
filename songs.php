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
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Moje Piosenki</h2>
            <button id="addSongButton" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Dodaj Piosenkę
            </button>
        </div>

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
            <button id="addPlaylistButton" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Dodaj Playlistę
            </button>
        </div>

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

        <div id="playlistsContainer" class="flex flex-wrap gap-4">
            <!-- Playlists -->
        </div>


        <div id="editPlaylistForm" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-50">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3">
                <h2 class="text-xl font-bold mb-4">Edytuj playlistę</h2>
                <form id="editPlaylistFormElement">
                <input type="hidden" id="playlistIdInput" name="playlistId" value="">

                    <div class="mb-4">
                        <label for="playlistPublic" class="block text-sm font-medium mb-2">Ustawienia prywatności:</label>
                        <select id="playlistPublic" name="playlistPublic" class="w-full p-2 border border-gray-700 rounded bg-gray-700 text-white focus:ring focus:ring-green-500">
                            <option value="1">Publiczna</option>
                            <option value="0">Prywatna</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Dodaj Piosenki:</label>
                        <div id="songsCheckboxList" class="space-y-2">
                            <!-- Song checkboxes will be populated dynamically -->
                        </div>
                    </div>

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

        <div id="playlistModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-50">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3">
        <h2 id="modalPlaylistTitle" class="text-xl font-bold mb-4 text-white"></h2>
        <p id="modalPlaylistDetails" class="text-white mb-4"></p>
        
        <div class="relative max-h-[360px] w-full overflow-hidden hide-scrollbar">
    <!-- Scrollujący obszar -->
    <div id="modalSongsContainer" class="flex flex-wrap justify-center w-full gap-4 max-h-[360px] overflow-y-scroll hide-scrollbar scroll-container">
</div>
</div>




        <div class="flex justify-end mt-4">
            <button id="closeModalButton" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Zamknij
            </button>
        </div>
    </div>
</div> 





    </div>
</main> 

<?php require_once 'footer.php'; ?>            

<script>
document.addEventListener('DOMContentLoaded', async () => {
        getSongs();
        getPlaylists();
});


const addSongButton = document.getElementById('addSongButton');
const addSongForm = document.getElementById('addSongForm');
const cancelSongButton = document.getElementById('cancelButton');
const cancelPlaylistButton = document.getElementById('cancelPlaylistButton');
const playlistModal = document.getElementById('playlistModal');
const closeModalButton = document.getElementById('closeModalButton');





addSongButton.addEventListener('click', () => {
    addSongForm.classList.remove('hidden');
});

cancelSongButton.addEventListener('click', () => {
    addSongForm.classList.add('hidden');
});

addPlaylistButton.addEventListener('click', () => {
    addPlaylistForm.classList.remove('hidden');
});

cancelPlaylistButton.addEventListener('click', () => {
    addPlaylistForm.classList.add('hidden');
});

addPlaylistFormElement.addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent the default form submission

    const formData = new FormData(this); // Prepare the form data to be sent
    const addPlaylistFormElement = document.getElementById('addPlaylistFormElement');
    const addPlaylistButton = document.getElementById('addPlaylistButton');
    const addPlaylistForm = document.getElementById('addPlaylistForm');

    fetch('api/spotify/add_playlist.php', {    // Send the form data to the server (use your actual server URL)
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())  // Parse the response as JSON
    .then(data => {
        if (data.success) {
            // Handle successful playlist addition
            alert('Playlist added successfully!');
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

let currentAudio = null;
let currentPlayButton = null;
let currentProgressBar = null;





function viewPlaylistDetails(button) {
    let isFormSubmitting = false;
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
            const response = await fetch(`api/spotify/get_playlist_details_songs.php?playlist_id=${playlistId}`);
            const result = await response.json();

            if (response.ok) {
                $songsCheckboxList.empty();

                result.songs.forEach(song => {
                    const songCheckbox = $(`
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="song-${song.id}" name="songs[]" value="${song.id}" class="form-checkbox h-5 w-5 text-green-500" ${song.in_playlist ? 'checked' : ''}>
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




</script>
<script src="assets/js/togglePlayPause.js"></script>
<script src="assets/js/getSongs.js"></script>
<script src="assets/js/getPlaylists.js"></script>
<script src="assets/js/player.js"></script>
<script src="assets/js/playlistModal.js"></script>


</body>
</html>
