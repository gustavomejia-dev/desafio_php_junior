<?php 

namespace App\controllers;
use App\http\Request;
use App\http\Response;
use App\models\ReservationRoom;
use App\models\Room;
use App\Traits\UserTrait;
use DateTime;
/**
 * Classe RoomController para gerenciamento de salas e reservas de salas.
 */
class RoomController extends Controller{
    use UserTrait;
    /**
     * Renderiza a página inicial de cadastro de sala.
     * 
     * @return void
     */
    public function index () {
        // echo "<pre>"; print_r($_SESSION);die;
        return $this->view('register/room/index', private:true);
    }
    
    function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
      /**
     * Renderiza a página de registro de uma nova sala.
     * 
     * @return void
     */
    public function register () {
      
        return $this->view('register/room/store', private: true);
    }
    /**
     * Lista todas as reservas de salas.
     * 
     * @return void
     */
    public function listBookings () {
        echo json_encode(Room::join('reservations', 'rooms.id', 'reservations.room_id')->get());
    }
    /**
     * Exclui uma reserva de sala.
     * 
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function deleteBooking (Request $request, Response $response) {
        
        ReservationRoom::delete($request::body());
    }
     /**
     * Registra uma nova reserva de sala.
     * 
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function registerBooking (Request $request, Response $response) {
        
        if (empty($request::body()['bookingRoomId']) 
            || 
                empty($request::body()['bookingRoomDate']) 
            || 
                empty($request::body()['bookingHourStart']) 
            || 
                empty($request::body()['bookingHourEnd'])) 
        {
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Opps! Preencha todos os campos,  por favor!'
            ], 400);
        }
        $now = new DateTime();
        $dateIncoming = $request::body()['bookingRoomDate'];
        $bookingDate = new DateTime($request::body()['bookingRoomDate']);
        $startTime = new DateTime("{$dateIncoming} {$request::body()['bookingHourStart']}");
        $endTime =  new DateTime("{$dateIncoming} {$request::body()['bookingHourEnd']}");
     
        $isReservartion = ReservationRoom::query("
                                                SELECT 
                                                    * 
                                                FROM 
                                                    gustavo_mejia.reservations 
                                                WHERE 

                                                        room_id = '{$request::body()['bookingRoomId']}'
                                                AND
                                                        (
                                                            start_time 
                                                                BETWEEN 
                                                                        '{$startTime->format('Y-m-d H:i:s')}' 
                                                                    AND 
                                                                        '{$endTime->format('Y-m-d H:i:s')}'
                                                                OR
                                                            end_time  
                                                                BETWEEN 
                                                                        '{$startTime->format('Y-m-d H:i:s')}' 
                                                                    AND 
                                                                        '{$endTime->format('Y-m-d H:i:s')}'
                                                        )        "
                                                    );
        if($isReservartion){
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Opps! Já tem uma reserva nessa sala!'
            ], 400);
        }

        if ($bookingDate < $now->setTime(0, 0)) {
         
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Opps! A data da reserva deve ser maior ou igual à data atual!'
            ], 400);
  
        }
        
        if ($startTime >= $endTime) {
           
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Opps! A hora de início deve ser menor que a hora de término.!'
            ], 400);
        }
        
        ReservationRoom::create(
            [
                'room_id' => $request::body()['bookingRoomId'],
                'user_id' => $this->getLoggedInUser()['id'], /** id do usuario */
                'start_time' =>  $startTime->format('Y-m-d H:i:s'),
                'end_time' => $endTime->format('Y-m-d H:i:s'),
            
            ]
            );
        return  $response::json([
            'error'   => false,
            'success' => true,
            'message' => 'Reserva Criada com Sucesso'
        ], 200);
    }   
     /**
     * Cadastra uma nova sala.
     * 
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function store (Request $request, Response $response) {
        if($this->validateGroupUser($this->getLoggedInUser()['group_id'])) {
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Somente Administradores podem fazer essa ação'
            ], 401);
        }
        if(!empty($request::body()['roomName'] 
            && 
                !empty($request::body()['roomCapacity']) 
            && 
                !empty($request::body()['roomLocation']))) 
        {       
                 Room::create([ 'name' => $request::body()['roomName'],
                    
                        'capacity' => $request::body()['roomCapacity'],

                        'location' => $request::body()['roomLocation']
    
                ]);

                return  $response::json([
                    'error'   => false,
                    'success' => true,
                    'message' => 'Sala Cadastrada com Sucesso'
                ], 200);

        }else{
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Opps!, Preencha todos os campos, por favor !'
            ], 400);
        }
        

       
    }
 /**
     * Lista uma sala específica por ID.
     * 
     * @param Request $request
     * @param Response $response
     * @param int $id ID da sala.
     * @return Response
     */
    public function listRoom (Request $request, Response $response, int $id) {
        $room = Room::where('id', $id)->get();
        if($room){
            return  $response::json([
                'error'   => false,
                'success' => true,
                'message' => $room
            ], 200);
        }

        return  $response::json([
            'error'   => true,
            'success' => false,
            'message' => 'Sala não encontrada'
        ], 404);
        
    }
    /**
     * Lista todas as salas.
     * 
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function listRooms (Request $request, Response $response) {
   
       echo json_encode(Room::all());

    }

     /**
     * Exclui uma sala.
     * 
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function delete (Request $request, Response $response){
        if($this->validateGroupUser($this->getLoggedInUser()['group_id'])) {
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Somente Administradores podem fazer essa ação'
            ], 401);
        }
       if(Room::where('id', $request::body())->get()) {
           
            Room::delete($request::body());
            return  $response::json([
                'error'   => false,
                'success' => true,
                'message' => 'Sala deletada com sucesso'
            ], 200);

        }

        return  $response::json([
            'error'   => true,
            'success' => false,
            'message' => 'Opps!, erro ao deletar a sala'
        ], 404);
       
    }
    /**
     * Edita os dados de uma sala.
     * 
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function edit (Request $request, Response $response) {
        if($this->validateGroupUser($this->getLoggedInUser()['group_id'])) {
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Somente Administradores podem fazer essa ação'
            ], 401);
        }
        if(Room::where('id', $request::body()['roomId'])->get()){
            Room::update(
                [
                    'name' => $request::body()['name'],
                    'location' => $request::body()['location'],
                    'capacity' => $request::body()['capacity']
                ],
                $request::body()['roomId']

                
            );
            return  $response::json([
                'error'   => false,
                'success' => true,
                'message' => 'Item Atualizado com Sucesso'
            ], 200);
            
        }
        return  $response::json([
            'error'   => true,
            'success' => false,
            'message' => 'Opps!, Sala não encontrada'
        ], 404);
    }
}   