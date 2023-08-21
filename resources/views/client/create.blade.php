<x-app-layout>
    <div class="container mt-4">
        <h2>Adicionar Cliente</h2>
        <form id="clientForm">
            @csrf

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="birthdate" class="form-label">Data de nascimento</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Sexo</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>

            <div class="mb-3">
                <select id="states" name="state" class="form-control">
                    <option value="">Selecione um estado</option>
                    <!-- Os options dos estados serão preenchidos via JavaScript/jQuery -->
                </select>
            </div>

            <div class="mb-3">
                <select id="cities" name="city" class="form-control">
                    <option value="">Selecione um município</option>
                    <!-- Os options dos municípios serão preenchidos via JavaScript/jQuery após a seleção de um estado -->
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" id="clearForm" class="btn btn-secondary ml-2">Limpar</button>
        </form>
    </div>

    <script>
        document.getElementById('clientForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = {
                cpf: e.target.cpf.value,
                name: e.target.name.value,
                birthdate: e.target.birthdate.value,
                gender: e.target.gender.value,
                address: e.target.address.value,
                state: e.target.state.value,
                city: e.target.city.value
            };

            axios.post('/api/clientes', formData)
                .then(response => {
                    if (response.data.success) {
                        alert('Client saved successfully!');
                        window.location.href =
                            "{{ route('clientes.index') }}";
                    } else {
                        alert('An error occurred while saving the client.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the client.');
                });
        });
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
    <script>
        document.getElementById('clearForm').addEventListener('click', function() {
            let form = document.getElementById('clientForm');
            form.reset();

            let citiesDropdown = document.getElementById('cities');
            while (citiesDropdown.firstChild) {
                citiesDropdown.removeChild(citiesDropdown.firstChild);
            }
            let defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Selecione um município';
            citiesDropdown.appendChild(defaultOption);
        });
    </script>
</x-app-layout>
