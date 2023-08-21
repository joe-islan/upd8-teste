<x-app-layout>
    <h2>Client Details</h2>
    
        <table class="table table-bordered">
            <tr>
                <th>CPF</th>
                <td id="client-cpf"></td>
            </tr>
            <tr>
                <th>Nome</th>
                <td id="client-name"></td>
            </tr>
            <tr>
                <th>Data de Nascimento</th>
                <td id="client-birthdate"></td>
            </tr>
            <tr>
                <th>Sexo</th>
                <td id="client-gender"></td>
            </tr>
            <tr>
                <th>Endereço</th>
                <td id="client-address"></td>
            </tr>
            <tr>
                <th>Estado</th>
                <td id="client-state"></td>
            </tr>
            <tr>
                <th>Cidade</th>
                <td id="client-city"></td>
            </tr>
        </table>
    
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Voltar Para a Lista</a>
    
        <script>
            function loadClientDetails(clientId) {
                axios.get(`/api/clientes/${clientId}`)
                    .then(function(response) {
                        let client = response.data.item;

                        document.getElementById('client-cpf').textContent = client.cpf;
                        document.getElementById('client-name').textContent = client.name;
                        document.getElementById('client-birthdate').textContent = client.birthdate;
                        document.getElementById('client-gender').textContent = client.gender;
                        document.getElementById('client-address').textContent = client.address;
                        document.getElementById('client-state').textContent = client.state;
                        document.getElementById('client-city').textContent = client.city;
    
                        // Atualizando o link de edição com o ID correto
                        document.getElementById('edit-link').href = `/clients/edit/${client.id}`;
                    })
                    .catch(function(error) {
                        console.error('Error loading client details:', error);
                    });
            }
    
            // Chamada da função ao carregar a página.
            let clientId = {{ $client->id }};
            loadClientDetails(clientId);
        </script>
    </x-app-layout>
    