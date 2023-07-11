
import './bootstrap';

const messageElement = document.getElementById('messageOutput');
const userMessageInput = document.getElementById('message-input');
const sendMessage = document.getElementById('chatForm');

let url = window.location;
let newUrl = new URL(url);
let userName = newUrl.searchParams.get('name');

sendMessage.addEventListener('submit', function (e) {
    e.preventDefault();
    if (userMessageInput.value !== '') {
        axios({
            method: 'post',
            url: '/messages',
            data: {
                username: 'ahmad',
                message: userMessageInput.value
            }
        })
    }
    
    userMessageInput.value = '';
});

window.Echo.channel('chat').listen('.chatting', (res) => {
     console.log(res);
     //messageElement.innerHTML += '<div><strong>'+res.userName+'</strong><span>'+res.message+'</span></div>'
 });