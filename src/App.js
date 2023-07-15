import './App.css';
import {Routes,Route } from 'react-router-dom';
import Signup from './pages/Signup';
import Login from './pages/Login';
import WorkSpaceCreation from './pages/WorkSpaceCreation';
import Dashboard from './pages/Dashboard';
import ProjectInfo from './pages/ProjectInfo';
import Project from './pages/Projects';

function App() {
  return (
  <>
    

  <Routes>
    <Route exact path='/' element={<Login/>} />
    <Route exact path='/signup' element={<Signup/>}/>
    <Route exact path='/signup/createworkspace' element={<WorkSpaceCreation/>}  />
    <Route exact path='/creator/dashboard' element={<Dashboard/> }/> 
    <Route exact path='/projectInfo' element={<ProjectInfo/>} /> 
    <Route exact path='/projects' element={<Project/>} />

  </Routes>
  

  </>
  );
}

export default App;
