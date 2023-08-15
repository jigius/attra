/**
 * jigius@gmail.com, 2023
 */

/**
 * Used for dependencies management
 */
const Dependency = function ()
{
  this._coll = {};
  this.register = function (key, value) {
    if (typeof key !== "string") {
      throw new TypeError("`key` argument is invalid");
    }
    if (this.registered(key)) {
      throw new Error("has been registered earlier");
    }
    if (!this._coll[key]) {
      this._coll[key] = $.Deferred();
    }
    this._coll[key].resolve(value);
  }

  /**
   * Check if a dependency with passed key is registered
   * @param {string} key
   * @returns {boolean}
   */
  this.registered = function (key) {
    if (typeof key !== "string") {
      throw new TypeError("`key` argument is invalid");
    }
    return (!!this._coll[key] && this._coll[key].state() !== "pending");
  }

  /**
   * Resolves requested dependencies
   * @param keys
   * @returns {Promise}
   */
  this.resolved = function (...keys) {
    let requested = [];
    const that = this;
    $.each(keys, function (idx, key) {
      if (typeof key !== "string") {
        throw new TypeError("`key` argument is invalid");
      }
      if (!that._coll[key]) {
        that._coll[key] = $.Deferred();
      }
      requested.push(that._coll[key]);
    });
    let ret;
    if (requested.length === 0) {
      throw new Error("key(s) of dependencies has not been defined");
    } else if (requested.length === 1) {
      ret = requested[0];
    } else {
      ret = $.when.apply($, requested);
    }
    return ret.promise();
  }
}

module.exports = Dependency;
