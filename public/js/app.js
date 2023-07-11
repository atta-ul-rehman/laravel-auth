// const messageElement = document.getElementById('messageOutput');
// const userMessageInput = document.getElementById('message-input');
// const sendMessage = document.getElementById('chatForm');

// let url = window.location;
// let newUrl = new URL(url);
// let userName = newUrl.searchParams.get('name');

// sendMessage.addEventListener('sumbit', function (e) {
//     e.preventDefault();
//     if (userMessageInput == '') {
//         axios({
//             method: 'POST',
//             url: '/sendMessage',
//             data: {
//                 username: userName,
//                 message: userMessageInput.value
//             }
//         })
//     }
   
//     userMessageInput.value = '';
// });

// window.channel('chat').listen('.chatting', (e) => {
//     console.log(e);
//     messageElement.innerHTML += '<div><strong>'+res.userName+'</strong><span>'+res.message+'</span></div>'
// });