/**
 * jigius@gmail.com, 2023
 */

/**
 * It's an event queue which is used in a project for the communication between various it's parts
 */
const EventQueue = function ()
{
  this.queue = {};
  this.event = function (id, flags) {
    if (typeof id !== "string") {
      throw new Error("`id` arguments is invalid");
    }
    flags ||= "memory";
    if (!this.queue[id]) {
      const callbacks = $.Callbacks(flags);
      this.queue[id] = {
        publish: callbacks.fire,
        subscribe: callbacks.add,
        unsubscribe: callbacks.remove,
        empty: function () {
          return !callbacks.has();
        }
      };
    }
    return this.queue[id];
  }
}
module.exports = EventQueue;
