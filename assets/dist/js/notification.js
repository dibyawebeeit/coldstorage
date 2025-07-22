
    function loadNotifications() {
  $.getJSON(BASE_URL + "notifications/fetch", function(data) {
    
    $('#notifyCount').text(data.unread_count);
    let items = '';
    let url = '';

    data.notifications.forEach(function(notify) {
      const date = new Date(notify.created_at);
      const formatted = date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      });

      if (notify.notification_type === 'machine') {
        url = BASE_URL + "machines";
      } else {
        url = BASE_URL + "compliences";
      }

      items += `
        <li>
          <i class="fa fa-file notiicon"></i>
          <a href="${url}" target="_blank">
            <div class="notitxt">${notify.message} <span>${formatted}</span></div>
          </a>
        </li>`;
    });

    $('#notificationItems').html(items || '<p class="dropdown-item text-muted">No new notifications</p>');
  });
}

$(document).ready(function() {
  loadNotifications();
  setInterval(loadNotifications, 30000);

  $('#notificationDropdown').on('click', function() {
    $.get(BASE_URL + "notifications/mark_read");
    $('#notifyCount').text(0);
  });
});