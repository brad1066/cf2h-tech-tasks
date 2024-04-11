jQuery(document).ready(function ($) {
  // Get the redirect and city query parameters from the URL

  const urlParams = new URLSearchParams(window.location.search);
  const redirect = urlParams.get('redirect') ?? '/';

  // Get the form elements and notice template
  const weatherForm = $('#weather-form');
  const loginForm = $('#login-form');
  const noticeTemplate = $('#notice_template');

  // A function to create a notice with a given text from a template
  function createNotice(text) {
    // Clone the notice, remove the ID, remove the hidden class, set
    // the text, add a click event to close the notice, adding it to
    // the notices container
    const notice = noticeTemplate.clone();
    notice.removeAttr('id');
    notice.removeClass('hidden')
    notice.find('.notice-text').text(text);
    notice.click(function () {
      closeNotice($(this));
    });
    notice.appendTo('#notices');
  }

  // When the login form is submitted
  loginForm?.submit(async function (e) {
    e.preventDefault();

    try {
      // Disable form while fetching data
      loginForm.find(':input:not([disabled="true"])').prop('disabled', true);

      // Get the username, password, and CSRF token from the form
      const username = $('#username').val();
      const password = $('#password').val();
      const csrfToken = $('#csrf-token').val();

      // If either the username or password is empty, show a notice and return
      if (!username || !password) {
        createNotice('Please fill in all fields');
        return;
      }

      // Fetch login data from the server using the username, password, and CSRF value
      response = await $.ajax({
        type: 'POST',
        url: '/actions/login.php',
        data: {
          username,
          password,
          csrfToken
        },
      })
        .then((response) => JSON.parse(response));

      // Clear any existing notices and show any notices if the response is not successful
      clearNotices();
      if (!response.success) {
        createNotice(response.message);
      } else {
        // Redirect to the redirect URL if the login is successful
        window.location.href = redirect;
      }
    }
    // Enable the form after fetching data
    finally { loginForm.find(':input').prop('disabled', false); }
  });

  // When the weather form is submitted
  weatherForm.submit(async function (e) {
    // Prevent the default form submission and get the CSRF token
    e.preventDefault();
    const csrfToken = $('#csrf-token').val();

    try {
      // Disable form while fetching data
      weatherForm.find(':input:not([disabled="true"])').prop('disabled', true);
      const city = $('#city').val();

      // If no city is provided, show a notice and return
      if (!city) {
        createNotice('Please enter the name of a city to continue');
        return;
      }

      // Fetch weather data from the server using the city name and CSRF value
      response = await $.ajax({
        type: 'POST',
        url: '/actions/weather.php',
        data: {
          city,
          csrfToken
        },
      })
        .then((response) => JSON.parse(response));

      // Clear any existing notices and show any notices if the response is not successful
      clearNotices();
      if ('success' in response && response.success) {
        createNotice(response.message);
        return;
      }

      // Update the weather data on the page
      const tempFound = !!response?.main?.temp;
      const temperature = tempFound ? `${response?.main?.temp + '\u00B0C'}` : 'No temperature data found';
      const feelsLike = tempFound ? `${', feels like ' + response?.main?.feels_like + '\u00B0C'}` : '';
      $('#location-name').text(response?.name ?? 'no location data found');
      $('#weather-output').text(response?.weather?.[0]?.description ?? 'No weather data found');
      $('#temp-output').text(`${temperature}${feelsLike}`);

      // Add the search query to the URL
      window.history.replaceState({}, '', `?city=${city}`);

    }
    catch (e) {
      // Show a notice if an error occurs
      createNotice('An error occurred while fetching the weather');
    }
    // Re-enable the form after fetching data
    finally { weatherForm.find(':input').prop('disabled', false); }
  });

  $('#logout-button').click(function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: '/actions/logout.php',
    })
      .then((response) => {
        response = JSON.parse(response);
        if (response.success) {
          window.location.href = '/login';
        }
      });
  });
});

function clearNotices() {
  // Remove all notices except the template
  $('.notice:not(#notice-template)').remove();
}

function closeNotice(notice) {
  // Close the notice by adding a class (for animation) and removing it after a delay
  notice.addClass('closing');
  setTimeout(function () {
    notice.remove();
  }, 500);
}