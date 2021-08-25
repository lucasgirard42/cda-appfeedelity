import {render, unmountComponentAtNode} from 'react-dom';
import React, {useEffect} from 'react';
import { usePaginatedFetch } from '../hooks/customersHooks';
import { Icon } from '../components/Icon';




function Customers(){

    const { items, load, loading, count, hasMore} = usePaginatedFetch('/api/customers') 

    useEffect(() => {
        load()
    }, [])

    return <div>
        {loading && 'chargement...'}
        {JSON.stringify(items)}
        <Title count={count}/>
        {/* <button onClick={load}>charger les customers</button> */}
        {hasMore && <button disabled={loading} className="btn btn-primary" onClick={load}> charger plus de clients </button>}
    </div>
}


function Title ({count}){
    // <Icon icon="customers" />
    return <h3>{count} Customer{count>1?'s':''}</h3>
}





class CustomerElement extends HTMLElement{

    connectedCallback(){
        render(<Customers/>, this)
    }

    disconnectedCallback(){
        unmountComponentAtNode(this)
    }
}


customElements.define('data-customers', CustomerElement)