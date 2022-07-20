// PAYEE
function updateCloudPayee() {
  $.post(
    window.location.pathname + "?r=sync-database/payee", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=payee-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// BOOKS
function updateCloudBooks() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// MAJOR ACCOUNTS
function updateCloudMajorAccount() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
//   SUB MAJOR ACCOUNTS
function updateCloudSubMajorAccount() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// NATURE OF TRANSACTION
function updateCloudNatureOfTransaction() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// MRD CLASSFICATION
function updateCloudMrdClassification() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// FUND SOURCE TYPE
function updateCloudFundSourceType() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// CHART OF ACCOUNTS
function updateCloudChartOfAccounts() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// SUB ACCOUNT 1
function updateCloudSubAccount1() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// SUB ACCOUNT 2
function updateCloudSubAccount2() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// RESPONSIBILITY CENTER
function updateCloudResponsibilityCenter() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// DOCUMENT RECIEVE
function updateCloudDocumetRecieve() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// FUND CLUSTER CODE
function updateCloudFundClusterCode() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// FINANCING SOURCE CODE
function updateCloudFinancingSourceCode() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// AUTHORIZATION CODE
function updateCloudAuthorizationCode() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// MFO/PAP CODES
function updateCloudMfoCodes() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
// FUND SOURCE
function updateCloudFundSource() {
  $.post(
    window.location.pathname + "?r=sync-database/books", // url
    {
      myData: "",
    }, // data to be submit
    function (data) {
      // success callback
      const d = JSON.parse(data);
      $.ajax({
        type: "post",
        url: "https://fisdticaraga.com/index.php?r=books-api/create",
        contentType: "application/json",
        data: JSON.stringify(d),
        dataType: "json",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        success: function (newdata) {
          console.log(newdata);
        },
      });
    }
  );
}
