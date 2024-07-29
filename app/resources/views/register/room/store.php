<?php include __DIR__ . '/../../layout/header.php' ?>

<div class="container mt-5">
    <h2>Gerenciamento de Salas</h2>
    <form id="registerRoomForm">
        <div class="form-group">
            <label for="roomName">Nome da Sala:</label>
            <input type="text" class="form-control" id="roomName" name="roomName" required>
        </div>
        <div class="form-group">
            <label for="roomCapacity">Capacidade:</label>
            <input type="number" class="form-control" id="roomCapacity" name="roomCapacity" required>
        </div>
        <div class="form-group">
            <label for="roomLocation">Localização:</label>
            <input type="text" class="form-control" id="roomLocation" name="roomLocation" required>
        </div>
        
    </form>
    <button type="button" id="btn_register_room" onclick="createRoomAndValidate()" class="btn btn-primary">Salvar</button>
    <div class="table-container">
        <h3>Salas Existentes</h3>
        <table class="table table-striped">
        <div class="table-scroll">>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Capacidade</th>
                        <th>Localização</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="roomTableBody">
                    <!-- Linhas de salas serão adicionadas aqui dinamicamente -->
                </tbody>
            </table>
        </div>
    </div>
    <script>$(document).ready(() => {
        loadRooms()
    })</script>
</div>

<?php include __DIR__ . '/../../layout/footer.php' ?>
