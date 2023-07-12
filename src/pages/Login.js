import React from 'react'
import '../App.css'
import Input from '../components/Input'
import { useState,useEffect } from 'react'
import ViewChanger from '../components/ViewChanger'
import Swal from 'sweetalert2'


export default function Login() {


  const [formData, setFormData] = useState({
    email: '',
    password: 0,})

      const changeHandler = (e) => {

      setFormData({
          ...formData,
          [e.target.id]: e.target.value
      });
    }

    const forgetPass=()=>
    {
      Swal.fire({
        title: 'Enter your email',
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: false,
        confirmButtonColor:'#1345B7',
        confirmButtonText: 'Send Email',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch(`//api.github.com/users/${login}`)
            .then(response => {
              if (!response.ok) {
                throw new Error(response.statusText)
              }
              return response.json()
            })
            .catch(error => {
              Swal.showValidationMessage(
                `Request failed: ${error}`
              )
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: `${result.value.login}'s avatar`,
            imageUrl: result.value.avatar_url
          })
        }
      })
    }


    useEffect(()=>{

    },[formData])

  return (
    <div className='div-background w-scren h-screen block md:flex justify-between md:pt-0 pt-6 px-5 md:px-10  items-center text-white'>
    
    <div className='text-center md:text-start md:mt-0 mt-14 '>
        <h1 className='text-[30px] md:text-[54px] main-login-font '>Whatever happens <br></br>  here, <b>stays</b> here</h1>
      </div>
 

      <div className='space-y-8 md:mr-14 backdrop-blur-sm py-4 md:px-10 px-0 border-[1px] rounded-xl mt-10' >
        
        <h1 className='text-[24px] md:text-[35px] form-fonts text-center font-light ' >Login</h1>

<form className='space-y-6 '>

  
        <Input type={"text"} id={"email"} value={formData.email} label={"Your Email"} onChange={changeHandler} />
        <Input type={"password"} id={"password"} value={formData.password} label={"Your Password"} onChange={changeHandler} />
        <Input type={"submit"} id={"login"} value={formData.password}  onChange={changeHandler} btnValue={'Login'} isLast={true}/>

       

     
</form>
<div className='text-right'>

 <button className=' form-fonts text-right' onClick={forgetPass}>Forgot Password?</button>
</div>

<ViewChanger text={'Not having an account?'} Btntxt={'Sign up'} linkto={'/signup'}/>
      </div>
   
    </div>
  )
}
