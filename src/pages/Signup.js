import React from 'react'
import '../App.css'
import { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom';
import Input from '../components/Input';
import ViewChanger from '../components/ViewChanger';
import AuthUser from './AuthUser';
import validator from 'validator';
import axios from 'axios'
import Swal from 'sweetalert2'

export default function Signup_page() {

  const navigate = useNavigate()

  const { http } = AuthUser();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
  })



  const changeHandler = (e) => {

    setFormData({
      ...formData,
      [e.target.id]: e.target.value
    });

  };

  useEffect(() => {

  }, [formData])

  const registeruser = async (event) => {
    event.preventDefault()
    if (formData.email === "" || formData.password === "" || formData.name === "") {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Fill all the fields !',
      })
    }
    else {
      if (validator.isEmail(formData.email)) {
        try {
          const response = axios.post(
            'http://127.0.0.1:8000/api/register', formData
          )
          if (response.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'You are successfully registered',
            })
            setTimeout(function () {
              navigate('/')
            }, 3000)
          }
          else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Something is wrong',
            })
          }

        } catch (err) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: `${err}`,
          })
        }
      }
    }
  }

  return (


    <div className='div-background w-scren h-screen block md:flex justify-between md:pt-0 pt-6 px-5 md:px-10  items-center text-white  '>

      <div className='text-center md:text-start md:mt-0 mt-14 '>
        <h1 className='text-[30px] md:text-[54px] main-login-font '>Whatever happens <br></br>  here, <b>stays</b> here</h1>
      </div>



      <div className='space-y-8 md:mr-14 backdrop-blur-sm py-4 md:px-10 px-0 border-[1px] rounded-xl mt-10' >

        <h1 className='text-[24px] md:text-[35px] form-fonts text-center font-light ' >Sign up</h1>

        <form className='space-y-6 ' onSubmit={registeruser}>

          <Input type={"text"} id={"name"} value={formData.name} label={"Your Name"} onChange={changeHandler} />
          <Input type={"text"} id={"email"} value={formData.email} label={"Your Email"} onChange={changeHandler} />
          <Input type={"password"} id={"password"} value={formData.password} label={"Your Password"} onChange={changeHandler} />
          <Input type={"submit"} id={"signup"} value={formData.password} label={""} onChange={changeHandler} btnValue={'Sign up'} isLast />




        </form>

        <ViewChanger text={'Already having an account?'} Btntxt={'Login'} linkto={'/'} />

      </div>

    </div>
  )
}
