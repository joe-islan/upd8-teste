<x-app-layout>
    <div class="container mt-4">
        <h2>Editar Cliente</h2>
        <form id="clientForm">

            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $client->id }}">

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="{{ $client->cpf }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $client->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="birthdate" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate"
                    value="{{ $client->birthdate }}" required>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Sexo</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="masculino" {{ $client->gender == 'masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="feminino" {{ $client->gender == 'feminino' ? 'selected' : '' }}>Feminino</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $client->address }}"
                    required>
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

            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>

    <script>
        document.getElementById('clientForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let clientId = e.target.id.value;

            let formData = {
                cpf: e.target.cpf.value,
                name: e.target.name.value,
                birthdate: e.target.birthdate.value,
                gender: e.target.gender.value,
                address: e.target.address.value,
                state: e.target.state.value,
                city: e.target.city.value
            };

            axios.put('/api/clientes/' + clientId, formData)
                .then(response => {
                    if (response.data.success) {
                        alert('Client updated successfully!');
                        window.location.href =
                            "{{ route('clientes.index') }}";
                    } else {
                        alert('An error occurred while updating the client.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the client.');
                });
        });
    </script>
    <script>
        $(document).ready(function() {
            var stateMap = {};

            function fetchStates(callback) {
                $.getJSON("https://servicodados.ibge.gov.br/api/v1/localidades/estados", function(data) {
                    $.each(data, function(index, state) {
                        stateMap[state.nome] = state.id;
                        $("#states").append('<option value="' + state.nome + '">' + state.nome +
                            '</option>');
                    });
                    callback();
                });
            }

            function fetchCities(stateName, callback) {
                var stateId = stateMap[stateName];
                $.getJSON("https://servicodados.ibge.gov.br/api/v1/localidades/estados/" + stateId + "/municipios",
                    function(municipiosData) {
                        $.each(municipiosData, function(index, city) {
                            $("#cities").append('<option value="' + city.nome + '">' + city.nome +
                                '</option>');
                        });
                        callback();
                    });
            }

            fetchStates(function() {
                let preSelectedState = "{{ $client->state }}";
                console.log(preSelectedState);
                if (preSelectedState) {
                    $("#states").val(preSelectedState).trigger('change');
                }
            });

            $("#states").change(function() {
                var selectedStateName = $(this).val();
                $("#cities").empty().append('<option value="">Selecione um município</option>');

                if (selectedStateName) {
                    fetchCities(selectedStateName, function() {
                        let preSelectedCity = "{{ $client->city }}";
                        if (preSelectedCity) {
                            $("#cities").val(preSelectedCity);
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
