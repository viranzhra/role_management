<x-app-layout>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="chat-container">
                        <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: scroll;">
                            <!-- Pesan dari chatbot akan muncul di sini -->
                        </div>

                        <textarea id="user-message" rows="4" placeholder="Ketik pesan Anda..." class="form-control mt-2"></textarea><br>
                        <button id="send-button" class="btn btn-primary mt-2">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('send-button').addEventListener('click', function() {
            const message = document.getElementById('user-message').value;

            if (message.trim() === '') {
                return;
            }

            // Menambahkan pesan pengguna ke dalam chat
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML += `<div><strong>You:</strong> ${message}</div>`;
            chatBox.scrollTop = chatBox.scrollHeight; // Scroll otomatis ke bawah

            // Mengirimkan pesan ke backend (Laravel)
            fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ question: message })
            })
            .then(response => response.json())
            .then(data => {
                // Menambahkan respon dari chatbot ke dalam chat
                chatBox.innerHTML += `<div><strong>Bot:</strong> ${data.answer}</div>`;
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll otomatis ke bawah
                document.getElementById('user-message').value = ''; // Kosongkan input
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</x-app-layout>
