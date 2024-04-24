const express = require('express');
const http = require('http');
const cors = require('cors');
const socketIo = require('socket.io');

const app = express();
// CORS 설정
// app.use(cors());
const server = http.createServer(app);

const rawBodySaver = (req, res, buf, encoding) => {
    if (buf && buf.length) {
        req.rawBodyBuf = buf;
        req.rawBody = buf.toString(encoding || 'utf8');
    }
};
app.use(express.json({ verify: rawBodySaver, limit: '50mb' }));
app.use(express.urlencoded({
    verify: rawBodySaver, parameterLimit: 100000, limit: '50mb', extended: false
}));

const io = socketIo(server, {
    cors: {
        origin: '*', // 허용할 도메인
        methods: ['GET', 'POST'], // 허용할 HTTP 메소드
        allowedHeaders: ['Content-Type'], // 허용할 헤더
        credentials: true // 인증 정보 포함 여부
    }
});
app.io = io;

io.on('connection', (socket) => {
    console.log('connected');

    socket.on('disconnect', () => {
        console.log('user disconnected');
    });

    socket.on('message', (data) => {
        if (data.room) {
            const roomChannel = 'Room' + data.room;
            io.to(roomChannel).emit('message', data);
        } else {
            io.emit('message', data);
        }
    });
});

const PORT = process.env.PORT || 3003;
server.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
