const express 	= require('express');
const app 		= express();
var path 		= require('path');
var fs  		= require('file-system');
const siofu 	= require("socketio-file-upload");
const server 	= app.use(siofu.router).listen(81, function() {
	console.log('server running on port 81');
});

var clients 		= {};
var connected_users = [];
const io 			= require('socket.io')(server);

 io.on('connection', function(socket) {

	//Socket File Upload
	fs.mkdir(path.resolve('./../../../uploads/chat_attachments'));
	var uploader = new siofu();
	uploader.mode = "0666"
	uploader.dir = path.resolve('./../../../uploads/chat_attachments');
	
	uploader.listen(socket);
	uploader.on("saved", function(event){
		event.file.clientDetail.filename = event.file.base;
		event.file.clientDetail.fileext = path.extname(event.file.name); 
	});

	//add new user's id to socket.
	socket.on('add-user', function(data) {
		clients[data.userId] = {
		  "socket": socket.id,
		};
		connected_users.push(data.userId);
		io.sockets.emit('connected-users', { users_connected: connected_users });
		
		console.log(clients);
	});

	//sending messsages to require person
	socket.on('send_msg', function(data){
	  if (clients[data.user_id]) {
		  console.log(data);
		io.sockets.connected[clients[ data.user_id ].socket].emit("send_msg", data);
	  } else {
		console.log("Text Message user doesn't exist");
	  }
	});

	//sending history messages
	socket.on('send_history_msg', function(data){
	console.log(clients);
		if (clients[data.user_id]) {
		  io.sockets.connected[clients[ data.user_id ].socket].emit("send_history_msg", data);
		} else {
		  console.log("Chat history user not exist");
		}
	});

	socket.on('send_files', function(data){
		if (clients[data.user_id]) {
		  io.sockets.connected[clients[ data.user_id ].socket].emit("send_files", data);
		} else {
		  console.log("Somehow file did not receive");
		}
	});

	//Removing the socket on disconnect
	socket.on('disconnect', function() {
		for(var name in clients) {
		  if(clients[name].socket === socket.id) {
			delete clients[name];
			break;
		  }
		}
	});
});