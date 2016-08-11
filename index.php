<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/env.php" ?>
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
      <link rel="stylesheet" href="<?php echo $theme_url ?>/css/main.css" />
    </head>
    <body>
      <div id="page" class="container m-t-3">
        <div class="row">
          <div class="col-xs-12">
            <div class="row">
              <header class="col-lg-6 m-b-2">
                <div class="card card-header">
                  <h1 class="h1">JQGen</h1>
                  <h2 class="h2">The jQuery Plugin Generator</h2>
                  <h4 class="lead"><small>{</small> Simple : Amazing <small>}</small></h4>
                  <p class="small lead">v1.5.3</p>
                  <a href="#" data-form="generator" class="btn btn-primary">
                    Quick Generate
                    <i class="fa fa-code" aria-hidden="true"></i>
                  </a>
                </div>
              </header>
              <form id="generator" action="<?php echo $generator_url ?>" method="get">
                <div class="col-lg-6 m-b-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-4">Plugin Style</label>
                    <div class="col-md-8">
                      <div class="custom-controls-stacked">
                        <label class="custom-control custom-radio">
                          <input id="jquerySimple" name="pluginStyle" type="radio" class="custom-control-input" value="jquerySimple" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">jQuery Simple</span>
                        </label>
                        <label class="custom-control custom-radio">
                          <input id="jqueryFnSimple" name="pluginStyle" type="radio" class="custom-control-input" value="jqueryFnSimple" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">jQuery fn Simple</span>
                        </label>
                        <label class="custom-control custom-radio">
                          <input id="jqueryFnApi" name="pluginStyle" type="radio" class="custom-control-input" value="jqueryFnApi" required>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">jQuery fn with API</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 m-b-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-4">Plugin Name</label>
                    <div class="col-md-8">
                      <input class="form-control" type="text" name="pluginName" placeholder="MyPlugin" required />
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 m-b-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-4">Plugin Version</label>
                    <div class="col-md-8">
                      <input class="form-control" type="text" name="pluginVersion" placeholder="1.0.0" />
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 m-b-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-4">
                      Plugin Description
                    </label>
                    <div class="col-md-8">
                      <textarea class="form-control" type="text" name="pluginDescription" placeholder="A jQuery plugin"></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 m-b-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-4">Plugin Prefix</label>
                    <div class="col-md-8">
                      <input class="form-control" type="text" name="pluginPrefix" placeholder="myplgn" />
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 m-b-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-4">Plugin Namespace</label>
                    <div class="col-md-8">
                      <input class="form-control" type="text" name="pluginNamespace" placeholder="myPlugin" />
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 m-b-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-4">
                      Default Options
                    </label>
                    <div class="col-md-8">
                      <textarea class="form-control" name="defaultOptions" placeholder="key1: 'value1', key2: 'value2', ..."></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 m-b-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-4">
                      Events
                    </label>
                    <div class="col-md-8">
                      <textarea class="form-control" name="pluginEvents" placeholder="add, update, delete, ..."></textarea>
                      <small class="small">included by default: init, ready, complete</small>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <fieldset class="form-group row">
                    <div class="col-lg-6 m-b-1">
                      <div class="form-group row">
                        <label class="col-form-label col-md-4">
                          API Methods<br />
                          <small class="small">(can use parenthesis and specify args)</small>
                        </label>
                        <div class="col-md-8">
                          <textarea class="form-control" name="apiMethods" placeholder="get, add(data), update(id, data), delete(id), ..."></textarea>
                          <small class="small">included by default: init, onReady, addBindings, prep</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 m-b-1">
                      <div class="form-group row">
                        <label class="col-form-label col-md-4">
                          Helper Methods
                        </label>
                        <div class="col-md-8">
                          <textarea class="form-control" name="helperMethods" placeholder="debug(label /* args */), test(input, type), trigger(event, args), ..."></textarea>
                          <small class="small">included by default: debug, test, trigger</small>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <div class="col-lg-12">
                  <fieldset class="form-group row">
                    <div class="col-lg-6 m-b-1">
                      <div class="form-group row">
                        <label class="col-form-label col-md-4">
                          Additional Global Vars<br />
                          <small class="small">(Variables for cached values or use across multiple objects)</small>
                        </label>
                        <div class="col-md-8">
                          <textarea class="form-control" name="globalVars" placeholder="debug, self, settings, ..."></textarea>
                          <small class="small">included by default: debug, self, settings</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 m-b-1">
                      <div class="form-group row">
                        <label class="col-form-label col-md-4">
                          Additional Global jQuery Vars<br />
                          <small class="small">(Cached jQuery object variables for use across multiple objects)</small>
                        </label>
                        <div class="col-md-8">
                          <textarea class="form-control" name="globalVarsJquery" placeholder="$events, $source, ..."></textarea>
                          <small class="small">included by default: $events, $source</small>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <div class="col-lg-12 m-b-1">
                  <label class="col-form-label">
                    Plugin Body<br />
                    <small class="small">(Placed inside init)</small>
                  </label>
                  <div id="editor_wrapper">
                    <pre id="editor"></pre>
                    <textarea id="pluginBody" class="form-control" name="pluginBody" rows="5"></textarea>
                  </div>
                </div>
                <div class="col-xs-12">
                  <fieldset class="form-group row">
                    <div class="col-lg-2 col-md-4 m-b-1">
                      <div class="col-form-label">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="includeLicense" id="includeLicense" checked>
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">Include license</span>
                        </label>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-8 m-b-1">
                      <div class="form-group row">
                        <label class="col-form-label col-md-5">License Type</label>
                        <div class="col-md-7">
                          <div class="form-control-static">Apache (more to come)</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 m-b-1">
                      <div class="form-group row">
                        <label class="col-form-label col-md-4">
                          License Organization
                        </label>
                        <div class="col-md-8">
                          <input class="form-control" type="text" name="licenseOrg" placeholder="jQGen" />
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <div class="col-xs-12">
                  <div class="form-group row">
                    <div class="col-md-12 m-b-1">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <a href="https://github.com/stairway/jqgen" target="_blank"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/365986a132ccd6a44c23a9169022c0b5c890c387/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png"></a>

      <script src="<?php echo $theme_url ?>/js/jquery-2.1.4.min.js"></script>
      <script src="<?php echo $theme_url ?>/js/ace-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
      <script src="<?php echo $theme_url ?>/js/main.js"></script>
    </body>
</html>
