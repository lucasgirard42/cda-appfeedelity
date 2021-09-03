import {render, unmountComponentAtNode} from 'react-dom';
import React, {useCallback, useEffect, useRef, useState} from 'react';
import { useFetch, usePaginatedFetch } from '../hooks/customersHooks';
import { Icon } from '../components/Icon';
import { Field } from '../components/Form';



const VIEW = 'VIEW'
const EDIT = 'EDIT'

function Customers({user}){

    const { items: customers, setItems: setCustomers, load, loading, count, hasMore} = usePaginatedFetch('/api/customers?user='+user) 

    const addCustomer = useCallback(customer => {
        setCustomers(customers => [customer, ...customers])
    }, [])

    const deleteCustomer = useCallback(customer => {
        setCustomers(customers => customers.filter(c => c != customer))
    }, [])

    const UpdateCustomerFirstName = useCallback((newCustomerFirstName, oldCustomerFirstName) => {
        
        setCustomers(customers => customers.map(c => c == oldCustomerFirstName ? newCustomerFirstName : c))
    }, [])

    useEffect(() => {
        load()
    }, [])

    return <div>
        {loading && 'chargement...'}   
        <Title count={count}/>
        {/* <button onClick={load}>charger les customers</button> */}
        {/* {hasMore && <button disabled={loading} className="btn btn-primary" onClick={load}> charger plus de clients </button>} */}
        {user && <CustomerForm user={user}  onCustomer={addCustomer}/>}

        {/*!!!!!! attention avec canEdit !!!!!!!!  canEdit={c.id == user }*/}
        {customers.map(c => 

        <Customer 
            key={c.id}  
            customer={c}  
            onDelete={deleteCustomer} 
            onUpdate={UpdateCustomerFirstName}
            />
        
        )} 
    </div>
}


function Title ({count}){
    // <Icon icon="customers" />
    return <h3>{count} Customer{count>1?'s':''}</h3>
}

const Customer = React.memo(({customer = null  , onDelete, onUpdate }) => {

    // Hooks
    const [state, setState] = useState(VIEW)
    

    // Events 
    const toggleEdit = useCallback(() => {
        setState(state => state == VIEW ? EDIT : VIEW)
    }, [])
    const onDeleteCallback = useCallback (() => {
        onDelete(customer)
    }, [customer])
    const onCustomer = useCallback((newCustomerFirstName) => {
        onUpdate(newCustomerFirstName, customer )
    }, [customer])

    const {loading: loadingDelete, load: callDelete} =  useFetch(customer['@id'], 'DELETE', onDeleteCallback )


    // Rendu 
    console.log('render');
    return <div className="row data-customers">
                <div className="col-sm-9"> 
                <strong>{customer.id} </strong>
            {state == VIEW ? 
            
            <strong>{customer.firstName}</strong> :
            <CustomerForm customer={customer} onCustomer={onCustomer} />
        }
             <p>
                <button className="btn btn-danger" onClick={callDelete.bind(this, null)} disabled={loadingDelete} >
                    delete
                </button>
                <button className="btn btn-secondary" onClick={toggleEdit}  >
                   EDIT
                </button>
            </p>
        </div>
    </div>
})

const CustomerForm = React.memo(({user, onCustomer, customer = null}) => {
    
    // Variables 
    const ref = useRef(null)

    const onSuccess = useCallback(customer => {
        onCustomer(customer)
        ref.current.value = ''
    }, [ref, onCustomer])

    // Hooks
    const method = customer ? 'PUT' : 'POST'
    const url = customer ? customer['@id'] : '/api/customers'
    const {load, loading, errors, clearError}  = useFetch(url, method, onSuccess)

    // Méthodes 
    const onSubmit = useCallback(e => {
        e.preventDefault()
        load({
            firstName: ref.current.value,
            user: "/api/users/" + user
        })
    }, [load, ref, user])

    // Effets 
    useEffect(() => {
        if (customer && customer.firstName && ref.current){
            ref.current.value = customer.firstName
        }
    }, [customer, ref]) 

    
    

    

    return (
      <div className="row" >
        <form className ="col-sm-3" onSubmit={onSubmit}>
            {customer == null && 
          <fieldset>
            <legend>
              <Icon icon="firsName" />
              Add customer
            </legend>
          </fieldset>
          }
          <Field
            name="firstName"
            ref={ref}
            error={errors["firstName"]}
            onChange={clearError.bind(this,'firstName')}
            required
            minLength={3}
          >
            FirstName
          </Field>
          <div className="form-group">
            <button className="btn btn-primary" disabled={loading}>
               {customer == null ? 'Envoyer' :  'Edit' }
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