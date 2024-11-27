<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breweries Viewer</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #login-section {
            max-width: 400px;
            margin: 20px auto;
        }
        #brewery-table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Brewery Viewer</h1>
        <div id="login-section" class="card p-4 shadow">
            <h2 class="text-center">Login</h2>
            <div class="mb-3">
                <label for="username" class="form-label">Username or Email</label>
                <input type="text" id="username" class="form-control" placeholder="Enter your username or email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button class="btn btn-primary w-100" id="login-button">Login</button>
        </div>

        <div id="breweries-section" style="display:none;">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Breweries List</h2>
                <button class="btn btn-danger" id="logout-button">Logout</button>
            </div>
            <table id="brewery-table" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Phone</th>
                        <th>Website</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        let token = localStorage.getItem('token') || '';
        let breweryTable;

        $(document).ready(function () {
            if (token) {
                showTable();
            }

            $('#login-button').click(function () {
                const username = $('#username').val();
                const password = $('#password').val();
                axios.post('/api/login', { username, password })
                    .then(function (response) {
                        token = response.data.token;
                        localStorage.setItem('token', token);
                        showTable();
                    })
                    .catch(function (error) {
                        alert('Login failed: ' + error.response.data.message);
                    });
            });

            // Logout event handler
            $('#logout-button').click(function () {
                token = '';
                localStorage.removeItem('token');
                location.reload();
            });
        });

        function showTable() {
            const page = parseInt(new URLSearchParams(window.location.search).get('page')) || 1;
            const perPage = parseInt(new URLSearchParams(window.location.search).get('per_page')) || 10;
            $('#login-section').hide();
            $('#breweries-section').show();
            initializeTable(page, perPage);
        }

        function initializeTable(page, perPage) {
            breweryTable = $('#brewery-table').DataTable({
                ajax: {
                    url: '/api/breweries',
                    type: 'GET',
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                    dataSrc: '', 
                    data: function (d) {
                        d.page = (d.start / d.length) + 1; 
                        d.per_page = d.length;
                    }
                },
                serverSide: true,
                processing: true,
                columns: [
                    { data: 'name', defaultContent: 'N/A' },
                    { data: 'brewery_type', defaultContent: 'N/A' },
                    { data: 'street', defaultContent: 'N/A' },
                    { data: 'city', defaultContent: 'N/A' },
                    { data: 'state', defaultContent: 'N/A' },
                    { data: 'country', defaultContent: 'N/A' },
                    { data: 'phone', defaultContent: 'N/A' },
                    { 
                        data: 'website_url', 
                        defaultContent: 'N/A',
                        render: function (data) {
                            return data ? `<a href="${data}" target="_blank">${data}</a>` : 'N/A';
                        }
                    }
                ],
                lengthMenu: [5, 10, 15, 20],
                pageLength: perPage,
                displayStart: (page - 1) * perPage,
                pagingType: "full_numbers",
                language: {
                    info: "Showing _START_ to _END_ entries",
                    infoEmpty: "No entries to show",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                initComplete: function () {
                    updateUrl(this.api().page() + 1, this.api().page.len());
                }
            });

            breweryTable.on('page', function () {
                const currentPage = breweryTable.page() + 1;
                const perPage = breweryTable.page.len();
                updateUrl(currentPage, perPage);
            });

            breweryTable.on('length', function (e, settings, len) {
                const currentPage = breweryTable.page() + 1;
                updateUrl(currentPage, len);
            });
        }

        function updateUrl(page, perPage) {
            const url = new URL(window.location);
            url.searchParams.set('page', page);
            url.searchParams.set('per_page', perPage);
            window.history.pushState({}, '', url);
        }
    </script>
</body>
</html>
