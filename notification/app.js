const {oracleConn, pgSQLConn} = require('./dbconf')
const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);

const io = require('socket.io')(server, {
    cors: {
        origin: true,
        methods: ["GET", "POST"],
        transports: ['websocket', 'polling'],
        credentials: true,
        allowEIO3: false
    }
});

app.use(express.static('public'));
server.listen(3000, () => {});

app.get('/', function(req, res){
    res.send('<p>node js v18.14.0</p>');
});

io.on('connection', function(socket){
    console.log('Socket Client Connected');

    socket.on('new_surat', (data) => {
        // Insert to DB
        let notifData = {
            tx_number: data.tx_number,
            from_org: data.user_except,
            to_org: data.tujuan_surat,
            notification: `Terdapat Surat Masuk baru dengan nomor surat ${data.no_surat}, harap segera diproses`,
        };

        pgSQLConn.any(`INSERT INTO notification (tx_number, from_org, to_org, notification) VALUES ('${notifData.tx_number}', '${notifData.from_org}', '${notifData.to_org}', '${notifData.notification}')`)
        .then((result) => {
            io.emit('new_surat', data)
        })
        .catch((err) => {
            console.log(err)
        });
        // socket.broadcast.emit('new_surat', data);
    })
})
