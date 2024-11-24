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

    </div>
</main>    

<?php require_once 'footer.php'; ?>            

<script>
    // Show the form when the "Add Song" button is clicked
    const addSongButton = document.getElementById('addSongButton');
    const addSongForm = document.getElementById('addSongForm');
    const cancelButton = document.getElementById('cancelButton');

    addSongButton.addEventListener('click', () => {
        addSongForm.classList.remove('hidden');
    });

    // Hide the form when the "Cancel" button is clicked
    cancelButton.addEventListener('click', () => {
        addSongForm.classList.add('hidden');
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
                    'w-48', 
                    'h-48', 
                    'bg-gray-700', 
                    'rounded-2xl', 
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
                    <!-- Progress Bar -->
                    <div 
                        class="progress-bar w-full bg-gray-600 rounded-t-2xl absolute top-0 left-0 transition-all duration-300 hover:h-2 z-10">
                        <div 
                            class="progress-fill h-full bg-green-400 rounded-t-2xl transition-all duration-300" 
                            style="width: 0%;">
                        </div>
                    </div>

                    <!-- Icon in the center -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <img src="media/spotify_icons/music-solid.svg" alt="Music Icon" class="w-20 h-20 -mt-12 text-gray-200" />
                    </div>

                    <!-- Title and Musician at the bottom -->
                    <div class="absolute bottom-2 left-2 right-2 text-center">
                        <h3 class="text-xl font-semibold text-white">${song.title}</h3>
                        <p class="text-sm text-gray-400">${song.musician}</p>
                    </div>

                    <!-- Play button -->
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
function togglePlayPause(button) {
    const filename = button.getAttribute('data-filename');
    const playIcon = button.querySelector('.playIcon');
    const songBox = button.closest('.w-48');
    const progressBar = songBox.querySelector('.progress-bar');
    const progressFill = progressBar.querySelector('.progress-fill');

    if (currentAudio && currentAudio.src.endsWith(filename)) {
        // Jeśli obecny utwór gra, wstrzymaj lub kontynuuj
        if (currentAudio.paused) {
            currentAudio.play();
            playIcon.src = 'media/spotify_icons/pause-solid.svg'; // Zmień ikonę na "pauza"
            progressBar.classList.add('active'); // Pokaż pasek postępu
        } else {
            currentAudio.pause();
            playIcon.src = 'media/storage_icons/play-solid.svg'; // Zmień ikonę na "odtwarzanie"
            progressBar.classList.remove('active'); // Ukryj pasek postępu
        }
    } else {
        // Jeśli wybrano inny utwór
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;

            if (currentPlayButton) {
                const prevPlayIcon = currentPlayButton.querySelector('.playIcon');
                prevPlayIcon.src = 'media/storage_icons/play-solid.svg'; // Resetuj poprzedni przycisk

                if (currentProgressBar) {
                    currentProgressBar.classList.remove('active'); // Ukryj poprzedni pasek postępu
                    currentProgressBar.querySelector('.progress-fill').style.width = '0%'; // Resetuj pasek
                }
            }
        }

        // Odtwórz nowy utwór
        currentAudio = new Audio(`uploads/songs/${filename}`);
        currentAudio.play();
        playIcon.src = 'media/spotify_icons/pause-solid.svg'; // Zmień ikonę na "pauza"
        currentPlayButton = button;
        currentProgressBar = progressBar;
        progressBar.classList.add('active'); // Pokaż pasek postępu

        // Aktualizuj pasek postępu
        currentAudio.addEventListener('timeupdate', () => {
            if (currentAudio && currentProgressBar) {
                const progress = (currentAudio.currentTime / currentAudio.duration) * 100;
                currentProgressBar.querySelector('.progress-fill').style.width = `${progress}%`;
            }
        });

        // Resetuj przycisk i pasek po zakończeniu utworu
        currentAudio.addEventListener('ended', () => {
            playIcon.src = 'media/storage_icons/play-solid.svg'; // Zresetuj do ikony "odtwarzanie"
            currentAudio = null;
            currentPlayButton = null;

            if (currentProgressBar) {
                currentProgressBar.classList.remove('active'); // Ukryj pasek postępu
                currentProgressBar.querySelector('.progress-fill').style.width = '0%'; // Resetuj pasek
                currentProgressBar = null;
            }
        });
    }

    // Dodaj nasłuchiwanie kliknięcia na pasek postępu
    progressBar.addEventListener('click', (event) => {
        const progressBarWidth = progressBar.offsetWidth; // Szerokość paska
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
