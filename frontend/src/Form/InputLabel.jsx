import React from 'react'
import PropTypes from 'prop-types'

function InputLabel({ label, htmlFor = null, ...attrs }) {
  return (
    <label className="input-label" htmlFor={htmlFor} {...attrs}>
      {label}
    </label>
  )
}

InputLabel.propTypes = {
  label: PropTypes.string.isRequired,
  htmlFor: PropTypes.string,
}

export default InputLabel
