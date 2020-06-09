/* eslint-env browser */
/* globals OPTOVICOM_DIST_PATH */

/** Dynamically set absolute public path from current protocol and host */
if (OPTOVICOM_DIST_PATH) {
  __webpack_public_path__ = OPTOVICOM_DIST_PATH; // eslint-disable-line no-undef, camelcase
}
