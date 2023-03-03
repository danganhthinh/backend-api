importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyD2KWZPBcioj2R8d_u4ygqxfmkaE8tI8-k",
    projectId: "bridge-54957",
    messagingSenderId: "788641492844",
    appId: "1:788641492844:web:b6bd3348f08927e6adcd49"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
