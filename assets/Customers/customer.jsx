import {render, unmountComponentAtNode} from 'react-dom';
import React, {useCallback, useEffect, useRef, useState} from 'react';
import { useFetch, usePaginatedFetch } from '../hooks/customersHooks';
import { Icon } from '../components/Icon';
import { Field } from '../components/Form';




function Customers({user}){

    const { items: customers, setItems: setCustomers, load, loading, count, hasMore} = usePaginatedFetch('/api/customers?user='+user) 

    const addCustomer = useCallback(customer => {
        setCustomers(customers => [customer, ...customers])
    }, [])

    const deleteCustomer = useCallback(customer => {
        setCustomers(customers => customers.filter(c => c != customer))
    }, [])

    useEffect(() => {
        load()
    }, [])

    return <div>
        {loading && 'chargement...'}   
        {/* {JSON.stringify(customers)} */}
        {user && <CustomerForm user={user}  onCustomer={addCustomer}/>}

        {/*!!!!!! attention avec canEdit !!!!!!!!  canEdit={c.id == user }*/}
        {customers.map(c => 

        <Customer key={c.id}  customer={c}  onDelete={deleteCustomer} />
        
        )} 
        <Title count={count}/>
        {/* <button onClick={load}>charger les customers</button> */}
        {hasMore && <button disabled={loading} className="btn btn-primary" onClick={load}> charger plus de clients </button>}
    </div>
}


function Title ({count}){
    // <Icon icon="customers" />
    return <h3>{count} Customer{count>1?'s':''}</h3>
}

const Customer = React.memo(({customer, onDelete }) => {

    const onDeleteCallback = useCallback (() => {
        onDelete(customer)
    }, [customer])
    const {loading: loadingDelete, load: callDelete} =  useFetch(customer['@id'], 'DELETE', onDeleteCallback )

    console.log('render');
    return <div className="row data-customers">
        <div className="col-sm-1">
            <strong>{customer.id}</strong>
        </div >
        <h4 className="col-sm-3"> 
             <strong>{customer.firstName}</strong>
        </h4>
        <div className="col-sm-1">
            <p>{customer.fidelityPoint}</p>
        </div>
        <div className="col-sm-3">
            <p>
                <button className="btn btn-danger" onClick={callDelete.bind(this, null)} disabled={loadingDelete} >
                    delete
                </button>
            </p>
        </div>
        <div className="col-sm-3">
            <p>
                <button className="btn btn-secondary"  >
                    EDIT
                </button>
            </p>
        </div>
    </div>
})

const CustomerForm = React.memo(({user, onCustomer}) => {
    
    const ref = useRef(null)
    const onSuccess = useCallback(customer => {
        onCustomer(customer)
        ref.current.value = ''
    }, [ref, onCustomer])
    const {load, loading, errors, clearError}  = useFetch('/api/customers', 'POST', onSuccess)
    const onSubmit = useCallback(e => {
        e.preventDefault()
        load({
            firstName: ref.current.value,
            user: "/api/users/" + user
        })
    }, [load, ref, user])

    

    return (
      <div className="well">
        <form onSubmit={onSubmit}>
          <fieldset>
            <legend>
              <Icon icon="firsName" />
              Ajout customer
            </legend>
          </fieldset>
          <Field
            name="firstName"
            help="client non conforme"
            ref={ref}
            error={errors["firstName"]}
            onChange={clearError.bind(this,'firstName')}
            required
            minLength={3}
          >
            Votre clients
          </Field>
          <div className="form-group">
            <button className="btn btn-primary" disabled={loading}>
              <Icon icon="paper-plane" /> Envoyer
            </button>
          </div>
        </form>
      </div>
    );
    
})




class CustomerElement extends HTMLElement{

    connectedCallback(){
        const user = parseInt(this.dataset.user, 10) || null
        render(<Customers user={user}/>, this)
    }

    disconnectedCallback(){
        unmountComponentAtNode(this)
    }
}


customElements.define('data-customers', CustomerElement)