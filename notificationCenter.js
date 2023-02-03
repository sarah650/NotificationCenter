window.onload = () => {
	const fetchNotifications = (user_id) => {
		fetch('http://'+window.location.host+'/api/notificationCenter.php?method=getNotificationsByUserId&user_id='+user_id)
		.then(data => data.json())
		.then((data) => {
			displayNotifications(data);
		}).catch(function (error) {
			console.log('request failed', error)
		});
	};
	
	const fetchNotificationsCount = (user_id) => {
		fetch('http://'+window.location.host+'/api/notificationCenter.php?method=getNotificationsCountByUserId&user_id='+user_id)
		.then(data => data.json())
		.then((data) => {
			let total = document.createElement("p");
			total.innerHTML = 'Total notifications: '+data;
			document.getElementById('total').appendChild(total);
			
		}).catch(function (error) {
			console.log('request failed', error)
		});
	}
	
	const fetchReadOrUnread = (user_id) => {
		fetch('http://'+window.location.host+'/api/notificationCenter.php?method=readOrUnread&user_id='+user_id)
		.then(data => data.json())
		.then((data) => {
			let readOrUnread = document.createElement("p");
			readOrUnread.innerHTML = 'Read: '+data.read+', unread: '+data.unread;
			document.getElementById('readOrUnread').appendChild(readOrUnread);
			
		}).catch(function (error) {
			console.log('request failed', error)
		});
	}
	
	const removeNotificationByUserId = (user_id,notification_id) => {
		fetch('http://'+window.location.host+'/api/notificationCenter.php?method=removeNotificationByUserId&user_id='+user_id+'&notification_id='+notification_id)
		.then(data => data.json())
		.then((data) => {
			displayNotifications(data);
		}).catch(function (error) {
			console.log('request failed', error)
		});
	}
	
	const displayNotifications = (data) => {
		let notificationsList = document.getElementById('notifications-list');

		if(data.length===0){
			let listItem = document.createElement("li");
			listItem.innerHTML = "no notification yet";
			notificationsList.appendChild(listItem);
		}
		else{
			
			data.forEach(notification => {
				let content='';
				notification.content.forEach(c =>{
					switch(notification.content_type){
						case 'artist':
						case 'playlist':
						case 'podcast':
						content=content+'</br>'+c.name;
						break;
						case 'album':
						content=content+'</br>'+c.name+'</br>'+c.artist;
						break;
						case 'track':
						content=content+'</br>'+c.name+'</br>'+c.album;
						break;
					}
				})
				let listItem = document.createElement("li");
				listItem.innerHTML = `
				${content} </br>
				${notification.description} </br>
				${notification.time} - ${notification.type} ${notification.content_type}
				`;
				notificationsList.appendChild(listItem);
			});
		}
	};
	
	document.getElementById("submit-btn").addEventListener("click", () => {
		document.getElementById('total').innerHTML="";
		document.getElementById('readOrUnread').innerHTML="";
		document.getElementById('notifications-list').innerHTML="";

		fetchNotifications(document.getElementById('user').value);
		fetchNotificationsCount(document.getElementById('user').value);
		fetchReadOrUnread(document.getElementById('user').value);
	});
	
};