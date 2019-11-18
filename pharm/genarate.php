<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nababa Pharmacy Management</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>


<body>
<div class="row">
        <div class="col-md-6">
          <!-- Panel Standard Mode -->
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Standard Mode</h3>
            </div>
            <div class="panel-body">
              <form class="form-horizontal fv-form fv-form-bootstrap" id="exampleStandardForm" autocomplete="off" novalidate="novalidate"><button type="submit" class="fv-hidden-submit" style="display: none; width: 0px; height: 0px; "></button>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Full name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="standard_fullName" data-fv-field="standard_fullName">
                  <small style="display: none; " class="help-block" data-fv-validator="notEmpty" data-fv-for="standard_fullName" data-fv-result="NOT_VALIDATED">The full name is required and cannot be empty</small></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Email</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="standard_email" data-fv-field="standard_email">
                  <small style="display: none; " class="help-block" data-fv-validator="notEmpty" data-fv-for="standard_email" data-fv-result="NOT_VALIDATED">The email address is required and cannot be empty</small><small style="display: none; " class="help-block" data-fv-validator="emailAddress" data-fv-for="standard_email" data-fv-result="NOT_VALIDATED">The email address is not valid</small></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Content</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" name="standard_content" rows="5" data-fv-field="standard_content"></textarea>
                  <small style="display: none; " class="help-block" data-fv-validator="notEmpty" data-fv-for="standard_content" data-fv-result="NOT_VALIDATED">The content is required and cannot be empty</small><small style="display: none; " class="help-block" data-fv-validator="stringLength" data-fv-for="standard_content" data-fv-result="NOT_VALIDATED">The content must be less than 500 characters long</small></div>
                </div>
                <div class="text-right">
                  <button type="submit" class="btn btn-primary" id="validateButton2">Submit</button>
                </div>
              </form>
            </div>
          </div>
          <!-- End Panel Standard Mode -->
        </div>
       
      </div>
</body>
</html>