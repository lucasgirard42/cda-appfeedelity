import React from "react";

export const Field = React.forwardRef(({ name, children, error, onChange, required, minLength}, ref) => {

  if (error) {
    help = error 
  }

  const className = (...arr) => arr.filter(Boolean).join(' ')

  return (
    <div className={className('form-group', error && 'has-error')}>
      <label htmlFor={name} className="control-label">{children}</label>
      <input ref={ref}  className="form-control" rows="10" name={name} id={name}  onChange={onChange} required={required} minLength={minLength}/>
    </div>
  );
});
