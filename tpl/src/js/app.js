const Dependency = require("./dependency");

const app = {
  dependency: new Dependency()
};

window.jQuery = window.$ = require("jquery");
$(function() {
  app.dependency.register('$', $)
});

const EventQueue = require("./event_queue")
app.dependency.register('EventQueue', new EventQueue());
app.dependency.register('Inputmask', require("inputmask"));
app.dependency.register('template', require("./micro-templating.escaped"));

/**
 * Project's fonts
 */
import "./fonts.js";

/**
 * Project's styles
 */
import "./styles.js";

/**
 * Exposes only one global variables for the accessing to miscellaneous functions
 */
window.App = app;
