<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Submission</title>
    <style>
        .error {
            color: red;
            font-size: 0.875em;
            margin-top: 0.25em;
        }
    </style>
</head>
<body>
<form id="myForm">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
        <div id="error-name" class="error"></div>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <div id="error-email" class="error"></div>
    </div>
    <div>
        <label for="message">Message:</label>
        <textarea id="message" name="message"></textarea>
        <div id="error-message" class="error"></div>
    </div>
    <button type="submit">Submit</button>
</form>

<div id="response"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('myForm').addEventListener('submit', function(event) {
            event.preventDefault();

            document.querySelectorAll('.error').forEach(function(el) {
                el.innerHTML = '';
            });

            let formData = new FormData(this);

            fetch('/submit-form', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('response').innerHTML = `<div style="color: green;">${data.message}</div>`;
                })
                .catch(error => {
                    if (error.errors) {
                        for (let field in error.errors) {
                            document.getElementById('error-' + field).innerHTML = error.errors[field].join(', ');
                        }
                    } else {
                        console.error('Error:', error);
                    }
                });
        });
    });
</script>
</body>
</html>
