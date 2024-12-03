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
            <h2 class="text-2xl font-bold">Publiczne playlisty</h2>
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
        getPlaylists(true);
});


const addSongButton = document.getElementById('addSongButton');
const addSongForm = document.getElementById('addSongForm');
const cancelSongButton = document.getElementById('cancelButton');
const cancelPlaylistButton = document.getElementById('cancelPlaylistButton');


let currentAudio = null;
let currentPlayButton = null;
let currentProgressBar = null;





</script>
<script src="assets/js/togglePlayPause.js"></script>
<script src="assets/js/getSongs.js"></script>
<script src="assets/js/getPlaylists.js"></script>
<script src="assets/js/player.js"></script>
<script src="assets/js/playlistModal.js"></script>

</body>
</html>
