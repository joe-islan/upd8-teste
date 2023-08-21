<x-app-layout>
    <div class="w-100 d-flex justify-content-between align-items-center">
        <img src="{{ asset('images/logo.jpg') }}" width="150px">

        <a href="{{ route('clientes.create') }}" class="btn btn-primary">Adicionar Cliente</a>
    </div>

    <form id="filterForm">
        <div class="row">
            <div class="col-md-2">
                <input type="text" class="form-control" name="cpf" placeholder="CPF">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="name" placeholder="Nome">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="birthdate" placeholder="Birthdate">
            </div>
            <div class="col-md-2">
                <select class="form-control" name="gender">
                    <option value="">Selecione o Sexo</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="states" name="state" class="form-control">
                    <option value="">Selecione um estado</option>
                    <!-- Os options dos estados serão preenchidos via JavaScript/jQuery -->
                </select>
            </div>
            <div class="col-md-2">
                <select id="cities" name="city" class="form-control">
                    <option value="">Selecione um município</option>
                    <!-- Os options dos municípios serão preenchidos via JavaScript/jQuery após a seleção de um estado -->
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <button type="button" id="clearFilter" class="btn btn-secondary ml-2">Limpar</button>
            </div>
        </div>
    </form>



    <table class="table table-bordered">
        <thead class="bg-light">
            <tr>
                <th>Actions</th>
                <th>CPF</th>
                <th>Nome</th>
                <th>Data de Nasc.</th>
                <th>sexo</th>
                <th>Endereço</th>
                <th>Estado</th>
                <th>Cidade</th>
            </tr>
        </thead>
        <tbody id="clients-list">
            <!-- Os dados dos clientes serão inseridos aqui pelo Axios -->
        </tbody>
    </table>

    <nav>
        <ul class="pagination" id="pagination">
            <!-- Links de paginação serão inseridos aqui pelo Axios -->
        </ul>
    </nav>

    <script>
        let currentPage = 1;

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            currentPage = 1;

            const formData = new FormData(e.target);
            const filters = Object.fromEntries(formData.entries());

            loadPage(currentPage, filters);
        });

        document.getElementById('clearFilter').addEventListener('click', function() {
            let form = document.getElementById('filterForm');
            form.reset();
            let citiesDropdown = document.getElementById('cities');
            while (citiesDropdown.firstChild) {
                citiesDropdown.removeChild(citiesDropdown.firstChild);
            }
            let defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Selecione um município';
            citiesDropdown.appendChild(defaultOption);
            loadPage(1);
        });



        function loadPage(page = 1, filters = {}) {
            currentPage = page;
            let url = `/api/clientes?page=${page}`;

            if (Object.keys(filters).length) {
                const filterParams = new URLSearchParams(filters).toString();
                url += `&${filterParams}`;
            }

            axios.get(url)
                .then(function(response) {
                    let clients = response.data.item.data;
                    let output = '';
                    clients.forEach(client => {
                        output += `
                        <tr>
                            <td>
                                <a href="{{ url('clients/') }}/${client.id}" class="btn btn-sm btn-primary">Show</a>
                                <a href="{{ url('clients/edit/') }}/${client.id}" class="btn btn-sm btn-success">Edit</a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${client.id}">Delete</button>
                            </td>
                            <td>${client.cpf}</td>
                            <td>${client.name}</td>
                            <td>${client.birthdate}</td>
                            <td>${client.gender}</td>
                            <td>${client.address}</td>
                            <td>${client.state}</td>
                            <td>${client.city}</td>
                        </tr>`;
                    });
                    document.getElementById('clients-list').innerHTML = output;

                    document.querySelectorAll('.delete-btn').forEach(button => {
                        button.addEventListener('click', function(e) {
                            const clientId = e.target.getAttribute('data-id');
                            axios.delete('/api/clientes/' + clientId)
                                .then(response => {
                                    if (response.data.success) {
                                        alert('Client deleted successfully!');
                                        loadPage();
                                    } else {
                                        alert('An error occurred while deleting the client.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while deleting the client.');
                                });
                        });
                    });


                    let pagination = '';
                    for (let i = 1; i <= response.data.item.last_page; i++) {
                        pagination +=
                            `<li class="page-item ${i === page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                    document.getElementById('pagination').innerHTML = pagination;

                    document.querySelectorAll('#pagination .page-link').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            let selectedPage = parseInt(e.target.getAttribute('data-page'));
                            loadPage(selectedPage);
                        });
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

        loadPage(currentPage);
    </script>

    <script>
        $(document).ready(function() {
            var stateMap = {};

            $.getJSON("https://servicodados.ibge.gov.br/api/v1/localidades/estados", function(data) {
                $.each(data, function(index, state) {
                    stateMap[state.nome] = state.id;
                    $("#states").append('<option value="' + state.nome + '">' + state.nome +
                        '</option>');
                });
            });

            $("#states").change(function() {
                var selectedStateName = $(this).val();
                if (selectedStateName) {
                    $("#cities").empty().append(
                        '<option value="">Selecione um município</option>');

                    var stateId = stateMap[selectedStateName];

                    $.getJSON("https://servicodados.ibge.gov.br/api/v1/localidades/estados/" + stateId +
                        "/municipios",
                        function(municipiosData) {
                            $.each(municipiosData, function(index, city) {
                                $("#cities").append('<option value="' + city.nome + '">' + city
                                    .nome + '</option>');
                            });
                        });
                }
            });
        });
    </script>
</x-app-layout>
