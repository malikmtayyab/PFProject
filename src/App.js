import './App.css';
import {Routes,Route } from 'react-router-dom';
import Signup from './pages/Signup';
import Login from './pages/Login';
import WorkSpaceCreation from './pages/WorkSpaceCreation';
import Dashboard from './pages/Dashboard';
import Project from './pages/Project';

function App() {
  return (
  <>
    

  <Routes>
    <Route exact path='/' element={<Login/>} />
    <Route exact path='/signup' element={<Signup/>}/>
    <Route exact path='/signup/createworkspace' element={<WorkSpaceCreation/>}  />
    <Route exact path='/creator/dashboard' element={<Dashboard/> }/> 
    <Route exact path='/project' element={<Project/>} /> 

  </Routes>
  

  </>
  );
}

export default App;
