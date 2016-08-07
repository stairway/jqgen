<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/env.php';?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/generator.php';?>
<!doctype html>
<html>
    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <meta http-equiv="x-ua-compatible" content="ie=edge" />
      <meta name="description" content="Connect Suite, Application Development & Technology - May 26, 2016" />
      <title>jQuery Plugin Generator</title>
      <link rel="stylesheet" href="<?php echo $theme_url ?>/css/font-awesome.min.css" />
      <link rel="stylesheet" href="<?php echo $theme_url ?>/css/bootstrap.min.css" />
      <link rel="stylesheet" href="<?php echo $theme_url ?>/css/generator.css" />
    </head>
    <body>
      <div id="toolbar" class="container-fluid shadow-bottom hidden">
        <div class="row">
          <div class="col-xs-12">
            <button id="raw" class="btn btn-secondary btn-sm">View Raw</button>
            <label class="custom-control custom-checkbox m-l-1">
              <input id="readOnly" name="readOnly" type="checkbox" class="custom-control-input">
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">
                <i class="fa fa-eye hidden-md-up" aria-hidden="true"></i>
                <span class="hidden-sm-down">
                  Read only
                </span>
              </span>
            </label>
            <label class="custom-control custom-checkbox m-l-1">
              <input id="softTabs" name="softTabs" type="checkbox" class="custom-control-input" checked>
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">
                <i class="fa fa-indent hidden-md-up" aria-hidden="true"></i>
                <span class="hidden-sm-down">Use real tabs</span>
              </span>
            </label>
            <label class="custom-control custom-checkbox m-l-1">
              <input id="showInvisibles" name="showInvisibles" type="checkbox" class="custom-control-input" checked>
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">
                <i class="fa fa-dot-circle-o hidden-md-up" aria-hidden="true"></i>
                <span class="hidden-sm-down">Show invisibles</span>
              </span>
            </label>
            <a id="detachToolbar" href="#" class="m-l-1">
              <i class="fa fa-external-link-square" aria-hidden="true"></i>
              Detach Toolbar
            </a>
          </div>
        </div>
      </div>
      <pre id="editor"></pre>
      <textarea id="pluginBody" class="hidden"><?php display() ?></textarea>

      <script src="<?php echo $theme_url ?>/js/jquery-2.1.4.min.js"></script>
      <script src="<?php echo $theme_url ?>/js/ace-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
      <script src="<?php echo $theme_url ?>/js/generator.js"></script>
    </body>
</html>