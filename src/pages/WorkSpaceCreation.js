import React from 'react'
import '../App.css'
import { useNavigate } from 'react-router-dom';
import Input from '../components/Input'
import { useState } from 'react'
import axios from 'axios'
import Swal from 'sweetalert2'
import AuthUser from './AuthUser';
export default function WorkSpaceCreation() {

  const navigate = useNavigate()
  const token = localStorage.getItem('token');
  const { user } = AuthUser();
  const [formData, setFormData] = useState({
    workspace: ''
  })
  const [image, setimageFile] = useState();

  const changeHandler = (e) => {

    setFormData({
      ...formData,
      [e.target.id]: e.target.value
    });

  }

  const imagechangeHandler = async (event) => {
    const file = event.target.files[0]

    Swal.fire({
      icon: 'warning',
      title: 'Profile Picture',
      text: 'Are you sure you want to upload?',
    })
      .then((willUpload) => {
        if (willUpload) {
          setimageFile(file)
        }
      });
    event.target.value = null
  }

  const createWorkSpace = async (event) => {
    event.preventDefault()
    if (formData.workspace === "") {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Fill all the fields !',
      })
    }
    else if (!image) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Profile picture in mandatory!',
      })
    }
    else {
      const form = new FormData();
      form.append('image', image);
      form.append('workspace_name', formData.workspace)
      form.append('access_token', token)
      try {
        const response = await axios.post(
          `http://127.0.0.1:8000/api/createworkspace`,
          form
        )
        if (response.data.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: `${response.data.message}`,
          })
          navigate('/creator/dashboard')
        }
        else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: `${response.data.message}`,
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

  return (



    <div className='div-background w-scren h-screen  flex-wrap flex-wrap-reverse justify-center flex md:justify-between md:pt-0 pt-6 px-5 md:px-10  items-center text-white  '>

      <div className='space-y-8 md:mr-14 backdrop-blur-sm py-4 md:px-10 px-10 border-[1px] shadow-md rounded-xl pb-10' >

        <form onSubmit={createWorkSpace}>

          <h1 className='text-[24px] md:text-[35px] form-fonts text-center font-light' >Details</h1>

          <div className='flex justify-center'>

            <div className='bg-white rounded-full w-20 p-4'>
              <label for='img'>

                <img className='cursor-pointer' src={process.env.PUBLIC_URL + '/assets/upload_image/upload.png'} alt='...' />
              </label>
              <input id='img' type='file' className='upload-bg hidden' name='image' onChange={imagechangeHandler} accept='image/*' />
            </div>
          </div>

          <Input type={"text"} id={"workspace"} value={formData.workspace} label={"Work Space Name"} onChange={changeHandler} />
          <Input type={"submit"} id={"create"} value={formData.password} label={""} onChange={changeHandler} btnValue={'Create'} isLast />
        </form>



      </div>

      <div className='text-center md:text-start px-5 md:mt-0 mt-14 '>
        <h1 className='text-[30px] md:text-[40px] main-login-font text-center'>Give your organization a <br></br> distinctive name that reflects  <br></br>its values, purpose, and <br></br> mission</h1>
      </div>


    </div>
  )
}
