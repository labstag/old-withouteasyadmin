const discardComments = require('postcss-discard-comments');
const autoprefixer = require('autoprefixer');

module.exports = {
  plugins: [
    autoprefixer({}),
    discardComments({
      removeAll: true
    })
  ]
}
