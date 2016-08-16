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
            <form id="editorOptions" class="form-inline">
              <button id="raw" class="btn btn-secondary btn-sm">
                <i class="fa fa-file-code-o hidden-md-up" aria-hidden="true"></i>
                <span class="hidden-sm-down">
                  View Raw
                </span>
              </button>
              <label class="custom-control custom-checkbox">
                <input id="readOnly" name="readOnly" type="checkbox" class="custom-control-input" checked>
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">
                  <i class="fa fa-eye hidden-md-up" aria-hidden="true"></i>
                  <span class="hidden-sm-down">
                    Read only
                  </span>
                </span>
              </label>
              <label class="custom-control custom-checkbox">
                <input id="softTabs" name="softTabs" type="checkbox" class="custom-control-input" checked>
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">
                  <i class="fa fa-indent hidden-md-up" aria-hidden="true"></i>
                  <span class="hidden-sm-down">Use real tabs</span>
                </span>
              </label>
              <div class="form-group">
                <label for="tabSize" class="form-control-label">
                  <span class="hidden-sm-down">Tab Size</span>
                  <input type="text" id="tabSize" name="tabSize" class="form-control" value="2" maxlength="1" />
                </label>
              </div>
              <label class="custom-control custom-checkbox">
                <input id="showInvisibles" name="showInvisibles" type="checkbox" class="custom-control-input" checked>
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">
                  <i class="fa fa-dot-circle-o hidden-md-up" aria-hidden="true"></i>
                  <span class="hidden-sm-down">Show invisibles</span>
                </span>
              </label>
              <label>
                <a id="detachToolbar" href="#">
                  <i class="fa fa-external-link-square" aria-hidden="true"></i>
                  <span class="hidden-sm-down">Detach Toolbar</span>
                </a>
              </label>
            </form>
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