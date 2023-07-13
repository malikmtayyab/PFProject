import React from 'react'
import Navbar from '../components/Navbar'
import '../App.css'
import ProgressBar from '../ProgressBar'
import TableView from '../components/TableView'
import Profile from '../components/Profile'

// import dashboard from '../../public/assets/bg_login_signup/'

export default function Dashboard() {
  return (
    <div className='bg-[#fafafa] lg:flex  block justify-between lg:overflow-hidden'>
      
      <Navbar creator={true}/>
      
      <div className='lg:h-screen  w-screen lg:w-1/2 executive-background lg:overflow-hidden '>

      <h1 className='text-center pt-10  text-2xl  main-login-font'>Name Workspace</h1>

      </div>




     <div className=' lg:w-1/2 lg:h-[93vh] text-center pt-14 overflow-x-hidden lg:overflow-hidden lg:overflow-y-scroll    '>
      <div>
       <h1 className='main-login-font text-3xl '>Your Projects</h1>

       
{/* <div className='border-2 py-5'> */}

<div className='lg:mt-20 mt-10 grid grid-cols-1 lg:grid-cols-2  mx-10  gap-6  '>



       {/* <TableView no={'No.'} name={'Name'} start={'Start Date'} end={'End Date'} progress={'Progress'} isTitle borderClass={'border-b-2'}/> */}
    
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       <TableView no={1} name={'Salman Tariq'} start={'10/01/2023'} end={'10/07/2023'} progress={'75'} />
       {/* <TableView no={2} name={'Malik Tayyab'} start={'10/01/2023'} end={'10/07/2023'} progress={'35'} /> */}

       

     </div>
{/* </div> */}

</div>

      </div>

<div className='fixed pt-10 top-3/4 '>
<Profile/>
</div>
</div>
 
  )
}
