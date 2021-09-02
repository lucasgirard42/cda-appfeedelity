import {render, unmountComponentAtNode} from 'react-dom';
import React, {useEffect} from 'react';
import { usePaginatedFetch } from '../hooks/customersHooks';
import { Icon } from '../components/Icon';




function Customers({user}){

    const { items: customers, load, loading, count, hasMore} = usePaginatedFetch('/api/customers?user='+user) 

    useEffect(() => {
        load()
    }, [])

    return <div>
        {loading && 'chargement...'}   
        {/* {JSON.stringify(customers)} */}
        {customers.map(c => <Customer key={c.id}  customer={c} />)}
        <Title count={count}/>
        {/* <button onClick={load}>charger les customers</button> */}
        {hasMore && <button disabled={loading} className="btn btn-primary" onClick={load}> charger plus de clients </button>}
    </div>
}


function Title ({count}){
    // <Icon icon="customers" />
    return <h3>{count} Customer{count>1?'s':''}</h3>
}

const Customer = React.memo(({customer, user}) => {
    console.log('render');
    return <div className="row data-customers">
        <div className="col-sm-1">
            <strong>{customer.id}</strong>
        </div >
        <h4 className="col-sm-3"> 
             <strong>{customer.firstName}</strong>
             <strong> {customer.lastName}</strong>
        </h4>
        <div className="col-sm-1">
            <p>{customer.fidelityPoint}</p>
        </div>
    </div>
})




class CustomerElement extends HTMLElement{

    connectedCallback(){
        const user = parseInt(this.dataset.user, 10)
        render(<Customers user={user}/>, this)
    }

    disconnectedCallback(){
        unmountComponentAtNode(this)
    }
}


customElements.define('data-customers', CustomerElement)