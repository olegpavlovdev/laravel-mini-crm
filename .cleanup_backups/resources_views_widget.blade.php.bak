<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Feedback Widget</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Send feedback</h5>
            <form id="widget-form" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control" placeholder="+123456789" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Attachments</label>
                    <input type="file" name="files[]" class="form-control" multiple>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
                <div id="result" class="mt-3"></div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        $('#widget-form').on('submit', function (e) {
        e.preventDefault();
        $('#result').removeClass().text('');
            const form = $('#widget-form')[0];
            const formData = new FormData(form);

            $.ajax({
                url: '/api/tickets',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#result').addClass('text-success').text('Thank you, your request has been submitted.');
                    $('#widget-form')[0].reset();
                },
                error: function (xhr) {
                    let text = 'An error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        text = Object.values(xhr.responseJSON.errors).flat().join(' ');
                    }
                    $('#result').addClass('text-danger').text(text);
                }
            });
    });
</script>

</body>
</html>
