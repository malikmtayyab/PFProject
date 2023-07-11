import React from 'react'
import { Link } from 'react-router-dom';

export default function ViewChanger({text,Btntxt,linkto}) {
  return (
    <div className='flex justify-center space-x-2 pb-2'>
    <h1>{text} </h1>


     <Link to={linkto}>
        
        {Btntxt}
        </Link>
    
    </div>
  )
}
