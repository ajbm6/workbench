
(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href=".html">Padosoft</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Padosoft_Workbench" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Padosoft/Workbench.html">Workbench</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Padosoft_Workbench_Parameters" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Padosoft/Workbench/Parameters.html">Parameters</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Padosoft_Workbench_Parameters_Action" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Action.html">Action</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Dir" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Dir.html">Dir</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Dirtype" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Dirtype.html">Dirtype</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Domain" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Domain.html">Domain</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Email" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Email.html">Email</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Git" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Git.html">Git</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_GitAction" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/GitAction.html">GitAction</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Githookenable" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Githookenable.html">Githookenable</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_IEnumerable" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/IEnumerable.html">IEnumerable</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Organization" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Organization.html">Organization</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Packagedescr" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Packagedescr.html">Packagedescr</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Packagekeywords" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Packagekeywords.html">Packagekeywords</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Packagename" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Packagename.html">Packagename</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Password" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Password.html">Password</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Sshhost" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Sshhost.html">Sshhost</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Sshpassword" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Sshpassword.html">Sshpassword</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Sshuser" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Sshuser.html">Sshuser</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_Type" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/Type.html">Type</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Parameters_User" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Parameters/User.html">User</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Padosoft_Workbench_Traits" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Padosoft/Workbench/Traits.html">Traits</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Padosoft_Workbench_Traits_Enumerable" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Traits/Enumerable.html">Enumerable</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Traits_Errorable" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Padosoft/Workbench/Traits/Errorable.html">Errorable</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Padosoft_Workbench_GitSimpleException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/GitSimpleException.html">GitSimpleException</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_GitSimpleWrapper" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/GitSimpleWrapper.html">GitSimpleWrapper</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_TransformText" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/TransformText.html">TransformText</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_Workbench" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/Workbench.html">Workbench</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_WorkbenchApiGeneration" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/WorkbenchApiGeneration.html">WorkbenchApiGeneration</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_WorkbenchChangelog" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/WorkbenchChangelog.html">WorkbenchChangelog</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_WorkbenchCopyThread" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/WorkbenchCopyThread.html">WorkbenchCopyThread</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_WorkbenchDoc" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/WorkbenchDoc.html">WorkbenchDoc</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_WorkbenchServiceProvider" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/WorkbenchServiceProvider.html">WorkbenchServiceProvider</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_WorkbenchSettings" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/WorkbenchSettings.html">WorkbenchSettings</a>                    </div>                </li>                            <li data-name="class:Padosoft_Workbench_WorkbenchVersion" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Padosoft/Workbench/WorkbenchVersion.html">WorkbenchVersion</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Padosoft.html", "name": "Padosoft", "doc": "Namespace Padosoft"},{"type": "Namespace", "link": "Padosoft/Workbench.html", "name": "Padosoft\\Workbench", "doc": "Namespace Padosoft\\Workbench"},{"type": "Namespace", "link": "Padosoft/Workbench/Parameters.html", "name": "Padosoft\\Workbench\\Parameters", "doc": "Namespace Padosoft\\Workbench\\Parameters"},{"type": "Namespace", "link": "Padosoft/Workbench/Traits.html", "name": "Padosoft\\Workbench\\Traits", "doc": "Namespace Padosoft\\Workbench\\Traits"},
            {"type": "Interface", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_getCostants", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::getCostants", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::isValidValue", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_getCostantsValues", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::getCostantsValues", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_getCostant", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::getCostant", "doc": "&quot;&quot;"},
            
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/GitSimpleException.html", "name": "Padosoft\\Workbench\\GitSimpleException", "doc": "&quot;Base class for exceptions thrown by the Git library.&quot;"},
                    
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/GitSimpleWrapper.html", "name": "Padosoft\\Workbench\\GitSimpleWrapper", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\GitSimpleWrapper", "fromLink": "Padosoft/Workbench/GitSimpleWrapper.html", "link": "Padosoft/Workbench/GitSimpleWrapper.html#method___construct", "name": "Padosoft\\Workbench\\GitSimpleWrapper::__construct", "doc": "&quot;GitSimpleWrapper constructor.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\GitSimpleWrapper", "fromLink": "Padosoft/Workbench/GitSimpleWrapper.html", "link": "Padosoft/Workbench/GitSimpleWrapper.html#method_git", "name": "Padosoft\\Workbench\\GitSimpleWrapper::git", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\GitSimpleWrapper", "fromLink": "Padosoft/Workbench/GitSimpleWrapper.html", "link": "Padosoft/Workbench/GitSimpleWrapper.html#method_setGitBinary", "name": "Padosoft\\Workbench\\GitSimpleWrapper::setGitBinary", "doc": "&quot;Sets the path to the Git binary.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\GitSimpleWrapper", "fromLink": "Padosoft/Workbench/GitSimpleWrapper.html", "link": "Padosoft/Workbench/GitSimpleWrapper.html#method_getGitBinary", "name": "Padosoft\\Workbench\\GitSimpleWrapper::getGitBinary", "doc": "&quot;Returns the path to the Git binary.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\GitSimpleWrapper", "fromLink": "Padosoft/Workbench/GitSimpleWrapper.html", "link": "Padosoft/Workbench/GitSimpleWrapper.html#method_setWorkingDirectory", "name": "Padosoft\\Workbench\\GitSimpleWrapper::setWorkingDirectory", "doc": "&quot;Sets the path to the Git binary.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\GitSimpleWrapper", "fromLink": "Padosoft/Workbench/GitSimpleWrapper.html", "link": "Padosoft/Workbench/GitSimpleWrapper.html#method_getWorkingDirectory", "name": "Padosoft\\Workbench\\GitSimpleWrapper::getWorkingDirectory", "doc": "&quot;Returns the path to the Git binary.&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Action.html", "name": "Padosoft\\Workbench\\Parameters\\Action", "doc": "&quot;Class Action&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Action", "fromLink": "Padosoft/Workbench/Parameters/Action.html", "link": "Padosoft/Workbench/Parameters/Action.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Action::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Action", "fromLink": "Padosoft/Workbench/Parameters/Action.html", "link": "Padosoft/Workbench/Parameters/Action.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Action::read", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Dir.html", "name": "Padosoft\\Workbench\\Parameters\\Dir", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Dir", "fromLink": "Padosoft/Workbench/Parameters/Dir.html", "link": "Padosoft/Workbench/Parameters/Dir.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Dir::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Dir", "fromLink": "Padosoft/Workbench/Parameters/Dir.html", "link": "Padosoft/Workbench/Parameters/Dir.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Dir::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Dir", "fromLink": "Padosoft/Workbench/Parameters/Dir.html", "link": "Padosoft/Workbench/Parameters/Dir.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Dir::isValidValue", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Dir", "fromLink": "Padosoft/Workbench/Parameters/Dir.html", "link": "Padosoft/Workbench/Parameters/Dir.html#method_adjustPath", "name": "Padosoft\\Workbench\\Parameters\\Dir::adjustPath", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Dirtype.html", "name": "Padosoft\\Workbench\\Parameters\\Dirtype", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Dirtype", "fromLink": "Padosoft/Workbench/Parameters/Dirtype.html", "link": "Padosoft/Workbench/Parameters/Dirtype.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Dirtype::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Dirtype", "fromLink": "Padosoft/Workbench/Parameters/Dirtype.html", "link": "Padosoft/Workbench/Parameters/Dirtype.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Dirtype::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Dirtype", "fromLink": "Padosoft/Workbench/Parameters/Dirtype.html", "link": "Padosoft/Workbench/Parameters/Dirtype.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Dirtype::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Domain.html", "name": "Padosoft\\Workbench\\Parameters\\Domain", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Domain", "fromLink": "Padosoft/Workbench/Parameters/Domain.html", "link": "Padosoft/Workbench/Parameters/Domain.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Domain::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Domain", "fromLink": "Padosoft/Workbench/Parameters/Domain.html", "link": "Padosoft/Workbench/Parameters/Domain.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Domain::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Domain", "fromLink": "Padosoft/Workbench/Parameters/Domain.html", "link": "Padosoft/Workbench/Parameters/Domain.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Domain::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Email.html", "name": "Padosoft\\Workbench\\Parameters\\Email", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Email", "fromLink": "Padosoft/Workbench/Parameters/Email.html", "link": "Padosoft/Workbench/Parameters/Email.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Email::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Email", "fromLink": "Padosoft/Workbench/Parameters/Email.html", "link": "Padosoft/Workbench/Parameters/Email.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Email::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Email", "fromLink": "Padosoft/Workbench/Parameters/Email.html", "link": "Padosoft/Workbench/Parameters/Email.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Email::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Git.html", "name": "Padosoft\\Workbench\\Parameters\\Git", "doc": "&quot;Class Git&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Git", "fromLink": "Padosoft/Workbench/Parameters/Git.html", "link": "Padosoft/Workbench/Parameters/Git.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Git::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Git", "fromLink": "Padosoft/Workbench/Parameters/Git.html", "link": "Padosoft/Workbench/Parameters/Git.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Git::read", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/GitAction.html", "name": "Padosoft\\Workbench\\Parameters\\GitAction", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\GitAction", "fromLink": "Padosoft/Workbench/Parameters/GitAction.html", "link": "Padosoft/Workbench/Parameters/GitAction.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\GitAction::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\GitAction", "fromLink": "Padosoft/Workbench/Parameters/GitAction.html", "link": "Padosoft/Workbench/Parameters/GitAction.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\GitAction::read", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Githookenable.html", "name": "Padosoft\\Workbench\\Parameters\\Githookenable", "doc": "&quot;Class Git&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Githookenable", "fromLink": "Padosoft/Workbench/Parameters/Githookenable.html", "link": "Padosoft/Workbench/Parameters/Githookenable.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Githookenable::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Githookenable", "fromLink": "Padosoft/Workbench/Parameters/Githookenable.html", "link": "Padosoft/Workbench/Parameters/Githookenable.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Githookenable::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Githookenable", "fromLink": "Padosoft/Workbench/Parameters/Githookenable.html", "link": "Padosoft/Workbench/Parameters/Githookenable.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Githookenable::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_getCostants", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::getCostants", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::isValidValue", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_getCostantsValues", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::getCostantsValues", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\IEnumerable", "fromLink": "Padosoft/Workbench/Parameters/IEnumerable.html", "link": "Padosoft/Workbench/Parameters/IEnumerable.html#method_getCostant", "name": "Padosoft\\Workbench\\Parameters\\IEnumerable::getCostant", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Organization.html", "name": "Padosoft\\Workbench\\Parameters\\Organization", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Organization", "fromLink": "Padosoft/Workbench/Parameters/Organization.html", "link": "Padosoft/Workbench/Parameters/Organization.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Organization::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Organization", "fromLink": "Padosoft/Workbench/Parameters/Organization.html", "link": "Padosoft/Workbench/Parameters/Organization.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Organization::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Organization", "fromLink": "Padosoft/Workbench/Parameters/Organization.html", "link": "Padosoft/Workbench/Parameters/Organization.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Organization::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Packagedescr.html", "name": "Padosoft\\Workbench\\Parameters\\Packagedescr", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagedescr", "fromLink": "Padosoft/Workbench/Parameters/Packagedescr.html", "link": "Padosoft/Workbench/Parameters/Packagedescr.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Packagedescr::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagedescr", "fromLink": "Padosoft/Workbench/Parameters/Packagedescr.html", "link": "Padosoft/Workbench/Parameters/Packagedescr.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Packagedescr::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagedescr", "fromLink": "Padosoft/Workbench/Parameters/Packagedescr.html", "link": "Padosoft/Workbench/Parameters/Packagedescr.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Packagedescr::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Packagekeywords.html", "name": "Padosoft\\Workbench\\Parameters\\Packagekeywords", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagekeywords", "fromLink": "Padosoft/Workbench/Parameters/Packagekeywords.html", "link": "Padosoft/Workbench/Parameters/Packagekeywords.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Packagekeywords::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagekeywords", "fromLink": "Padosoft/Workbench/Parameters/Packagekeywords.html", "link": "Padosoft/Workbench/Parameters/Packagekeywords.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Packagekeywords::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagekeywords", "fromLink": "Padosoft/Workbench/Parameters/Packagekeywords.html", "link": "Padosoft/Workbench/Parameters/Packagekeywords.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Packagekeywords::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Packagename.html", "name": "Padosoft\\Workbench\\Parameters\\Packagename", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagename", "fromLink": "Padosoft/Workbench/Parameters/Packagename.html", "link": "Padosoft/Workbench/Parameters/Packagename.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Packagename::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagename", "fromLink": "Padosoft/Workbench/Parameters/Packagename.html", "link": "Padosoft/Workbench/Parameters/Packagename.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Packagename::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagename", "fromLink": "Padosoft/Workbench/Parameters/Packagename.html", "link": "Padosoft/Workbench/Parameters/Packagename.html#method_prova", "name": "Padosoft\\Workbench\\Parameters\\Packagename::prova", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Packagename", "fromLink": "Padosoft/Workbench/Parameters/Packagename.html", "link": "Padosoft/Workbench/Parameters/Packagename.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Packagename::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Password.html", "name": "Padosoft\\Workbench\\Parameters\\Password", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Password", "fromLink": "Padosoft/Workbench/Parameters/Password.html", "link": "Padosoft/Workbench/Parameters/Password.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Password::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Password", "fromLink": "Padosoft/Workbench/Parameters/Password.html", "link": "Padosoft/Workbench/Parameters/Password.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Password::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Password", "fromLink": "Padosoft/Workbench/Parameters/Password.html", "link": "Padosoft/Workbench/Parameters/Password.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Password::isValidValue", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Password", "fromLink": "Padosoft/Workbench/Parameters/Password.html", "link": "Padosoft/Workbench/Parameters/Password.html#method_testPassword", "name": "Padosoft\\Workbench\\Parameters\\Password::testPassword", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Sshhost.html", "name": "Padosoft\\Workbench\\Parameters\\Sshhost", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshhost", "fromLink": "Padosoft/Workbench/Parameters/Sshhost.html", "link": "Padosoft/Workbench/Parameters/Sshhost.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Sshhost::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshhost", "fromLink": "Padosoft/Workbench/Parameters/Sshhost.html", "link": "Padosoft/Workbench/Parameters/Sshhost.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Sshhost::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshhost", "fromLink": "Padosoft/Workbench/Parameters/Sshhost.html", "link": "Padosoft/Workbench/Parameters/Sshhost.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Sshhost::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Sshpassword.html", "name": "Padosoft\\Workbench\\Parameters\\Sshpassword", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshpassword", "fromLink": "Padosoft/Workbench/Parameters/Sshpassword.html", "link": "Padosoft/Workbench/Parameters/Sshpassword.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Sshpassword::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshpassword", "fromLink": "Padosoft/Workbench/Parameters/Sshpassword.html", "link": "Padosoft/Workbench/Parameters/Sshpassword.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Sshpassword::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshpassword", "fromLink": "Padosoft/Workbench/Parameters/Sshpassword.html", "link": "Padosoft/Workbench/Parameters/Sshpassword.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Sshpassword::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Sshuser.html", "name": "Padosoft\\Workbench\\Parameters\\Sshuser", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshuser", "fromLink": "Padosoft/Workbench/Parameters/Sshuser.html", "link": "Padosoft/Workbench/Parameters/Sshuser.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Sshuser::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshuser", "fromLink": "Padosoft/Workbench/Parameters/Sshuser.html", "link": "Padosoft/Workbench/Parameters/Sshuser.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Sshuser::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Sshuser", "fromLink": "Padosoft/Workbench/Parameters/Sshuser.html", "link": "Padosoft/Workbench/Parameters/Sshuser.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\Sshuser::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/Type.html", "name": "Padosoft\\Workbench\\Parameters\\Type", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Type", "fromLink": "Padosoft/Workbench/Parameters/Type.html", "link": "Padosoft/Workbench/Parameters/Type.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\Type::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\Type", "fromLink": "Padosoft/Workbench/Parameters/Type.html", "link": "Padosoft/Workbench/Parameters/Type.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\Type::read", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench\\Parameters", "fromLink": "Padosoft/Workbench/Parameters.html", "link": "Padosoft/Workbench/Parameters/User.html", "name": "Padosoft\\Workbench\\Parameters\\User", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\User", "fromLink": "Padosoft/Workbench/Parameters/User.html", "link": "Padosoft/Workbench/Parameters/User.html#method___construct", "name": "Padosoft\\Workbench\\Parameters\\User::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\User", "fromLink": "Padosoft/Workbench/Parameters/User.html", "link": "Padosoft/Workbench/Parameters/User.html#method_read", "name": "Padosoft\\Workbench\\Parameters\\User::read", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Parameters\\User", "fromLink": "Padosoft/Workbench/Parameters/User.html", "link": "Padosoft/Workbench/Parameters/User.html#method_isValidValue", "name": "Padosoft\\Workbench\\Parameters\\User::isValidValue", "doc": "&quot;&quot;"},
            
            {"type": "Trait", "fromName": "Padosoft\\Workbench\\Traits", "fromLink": "Padosoft/Workbench/Traits.html", "link": "Padosoft/Workbench/Traits/Enumerable.html", "name": "Padosoft\\Workbench\\Traits\\Enumerable", "doc": "&quot;Class Enumerable&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Enumerable", "fromLink": "Padosoft/Workbench/Traits/Enumerable.html", "link": "Padosoft/Workbench/Traits/Enumerable.html#method_getCostants", "name": "Padosoft\\Workbench\\Traits\\Enumerable::getCostants", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Enumerable", "fromLink": "Padosoft/Workbench/Traits/Enumerable.html", "link": "Padosoft/Workbench/Traits/Enumerable.html#method_isValidValue", "name": "Padosoft\\Workbench\\Traits\\Enumerable::isValidValue", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Enumerable", "fromLink": "Padosoft/Workbench/Traits/Enumerable.html", "link": "Padosoft/Workbench/Traits/Enumerable.html#method_getCostantsValues", "name": "Padosoft\\Workbench\\Traits\\Enumerable::getCostantsValues", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Enumerable", "fromLink": "Padosoft/Workbench/Traits/Enumerable.html", "link": "Padosoft/Workbench/Traits/Enumerable.html#method_getCostant", "name": "Padosoft\\Workbench\\Traits\\Enumerable::getCostant", "doc": "&quot;&quot;"},
            
            {"type": "Trait", "fromName": "Padosoft\\Workbench\\Traits", "fromLink": "Padosoft/Workbench/Traits.html", "link": "Padosoft/Workbench/Traits/Errorable.html", "name": "Padosoft\\Workbench\\Traits\\Errorable", "doc": "&quot;Class Errorable&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Errorable", "fromLink": "Padosoft/Workbench/Traits/Errorable.html", "link": "Padosoft/Workbench/Traits/Errorable.html#method_getArrErrors", "name": "Padosoft\\Workbench\\Traits\\Errorable::getArrErrors", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Errorable", "fromLink": "Padosoft/Workbench/Traits/Errorable.html", "link": "Padosoft/Workbench/Traits/Errorable.html#method_addError", "name": "Padosoft\\Workbench\\Traits\\Errorable::addError", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Errorable", "fromLink": "Padosoft/Workbench/Traits/Errorable.html", "link": "Padosoft/Workbench/Traits/Errorable.html#method_addArrErrors", "name": "Padosoft\\Workbench\\Traits\\Errorable::addArrErrors", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Errorable", "fromLink": "Padosoft/Workbench/Traits/Errorable.html", "link": "Padosoft/Workbench/Traits/Errorable.html#method_resetErrors", "name": "Padosoft\\Workbench\\Traits\\Errorable::resetErrors", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Traits\\Errorable", "fromLink": "Padosoft/Workbench/Traits/Errorable.html", "link": "Padosoft/Workbench/Traits/Errorable.html#method_hasErrors", "name": "Padosoft\\Workbench\\Traits\\Errorable::hasErrors", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/TransformText.html", "name": "Padosoft\\Workbench\\TransformText", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\TransformText", "fromLink": "Padosoft/Workbench/TransformText.html", "link": "Padosoft/Workbench/TransformText.html#method___construct", "name": "Padosoft\\Workbench\\TransformText::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\TransformText", "fromLink": "Padosoft/Workbench/TransformText.html", "link": "Padosoft/Workbench/TransformText.html#method_transformReadmeMd", "name": "Padosoft\\Workbench\\TransformText::transformReadmeMd", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/Workbench.html", "name": "Padosoft\\Workbench\\Workbench", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\Workbench", "fromLink": "Padosoft/Workbench/Workbench.html", "link": "Padosoft/Workbench/Workbench.html#method_handle", "name": "Padosoft\\Workbench\\Workbench::handle", "doc": "&quot;Execute the console command.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Workbench", "fromLink": "Padosoft/Workbench/Workbench.html", "link": "Padosoft/Workbench/Workbench.html#method_transformReadmeMd", "name": "Padosoft\\Workbench\\Workbench::transformReadmeMd", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Workbench", "fromLink": "Padosoft/Workbench/Workbench.html", "link": "Padosoft/Workbench/Workbench.html#method_setWorkbenchSettings", "name": "Padosoft\\Workbench\\Workbench::setWorkbenchSettings", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Workbench", "fromLink": "Padosoft/Workbench/Workbench.html", "link": "Padosoft/Workbench/Workbench.html#method_getWorkbenchSettings", "name": "Padosoft\\Workbench\\Workbench::getWorkbenchSettings", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Workbench", "fromLink": "Padosoft/Workbench/Workbench.html", "link": "Padosoft/Workbench/Workbench.html#method_enableTravis", "name": "Padosoft\\Workbench\\Workbench::enableTravis", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Workbench", "fromLink": "Padosoft/Workbench/Workbench.html", "link": "Padosoft/Workbench/Workbench.html#method_enableScrutinizer", "name": "Padosoft\\Workbench\\Workbench::enableScrutinizer", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Workbench", "fromLink": "Padosoft/Workbench/Workbench.html", "link": "Padosoft/Workbench/Workbench.html#method___get", "name": "Padosoft\\Workbench\\Workbench::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\Workbench", "fromLink": "Padosoft/Workbench/Workbench.html", "link": "Padosoft/Workbench/Workbench.html#method___set", "name": "Padosoft\\Workbench\\Workbench::__set", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/WorkbenchApiGeneration.html", "name": "Padosoft\\Workbench\\WorkbenchApiGeneration", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchApiGeneration", "fromLink": "Padosoft/Workbench/WorkbenchApiGeneration.html", "link": "Padosoft/Workbench/WorkbenchApiGeneration.html#method___construct", "name": "Padosoft\\Workbench\\WorkbenchApiGeneration::__construct", "doc": "&quot;&lt;ul&gt;\n&lt;li&gt;WorkbenchApiGeneration constructor.&lt;\/li&gt;\n&lt;\/ul&gt;\n&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchApiGeneration", "fromLink": "Padosoft/Workbench/WorkbenchApiGeneration.html", "link": "Padosoft/Workbench/WorkbenchApiGeneration.html#method_apigeneration", "name": "Padosoft\\Workbench\\WorkbenchApiGeneration::apigeneration", "doc": "&quot;*&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchApiGeneration", "fromLink": "Padosoft/Workbench/WorkbenchApiGeneration.html", "link": "Padosoft/Workbench/WorkbenchApiGeneration.html#method_apiSamiGeneration", "name": "Padosoft\\Workbench\\WorkbenchApiGeneration::apiSamiGeneration", "doc": "&quot;*&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchApiGeneration", "fromLink": "Padosoft/Workbench/WorkbenchApiGeneration.html", "link": "Padosoft/Workbench/WorkbenchApiGeneration.html#method_transformReadmeMd", "name": "Padosoft\\Workbench\\WorkbenchApiGeneration::transformReadmeMd", "doc": "&quot;&lt;ul&gt;\n&lt;li&gt;@param $readmepathsource\n\n&lt;ul&gt;\n&lt;li&gt;@param $readmepathdestination&lt;\/li&gt;\n&lt;\/ul&gt;&lt;\/li&gt;\n&lt;\/ul&gt;\n&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchApiGeneration", "fromLink": "Padosoft/Workbench/WorkbenchApiGeneration.html", "link": "Padosoft/Workbench/WorkbenchApiGeneration.html#method_dummy", "name": "Padosoft\\Workbench\\WorkbenchApiGeneration::dummy", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/WorkbenchChangelog.html", "name": "Padosoft\\Workbench\\WorkbenchChangelog", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchChangelog", "fromLink": "Padosoft/Workbench/WorkbenchChangelog.html", "link": "Padosoft/Workbench/WorkbenchChangelog.html#method___construct", "name": "Padosoft\\Workbench\\WorkbenchChangelog::__construct", "doc": "&quot;WorkbenchChangelog constructor.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchChangelog", "fromLink": "Padosoft/Workbench/WorkbenchChangelog.html", "link": "Padosoft/Workbench/WorkbenchChangelog.html#method_composeChangelog", "name": "Padosoft\\Workbench\\WorkbenchChangelog::composeChangelog", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchChangelog", "fromLink": "Padosoft/Workbench/WorkbenchChangelog.html", "link": "Padosoft/Workbench/WorkbenchChangelog.html#method_question", "name": "Padosoft\\Workbench\\WorkbenchChangelog::question", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchChangelog", "fromLink": "Padosoft/Workbench/WorkbenchChangelog.html", "link": "Padosoft/Workbench/WorkbenchChangelog.html#method_writeChangeLog", "name": "Padosoft\\Workbench\\WorkbenchChangelog::writeChangeLog", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchChangelog", "fromLink": "Padosoft/Workbench/WorkbenchChangelog.html", "link": "Padosoft/Workbench/WorkbenchChangelog.html#method_getChanges", "name": "Padosoft\\Workbench\\WorkbenchChangelog::getChanges", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/WorkbenchCopyThread.html", "name": "Padosoft\\Workbench\\WorkbenchCopyThread", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchCopyThread", "fromLink": "Padosoft/Workbench/WorkbenchCopyThread.html", "link": "Padosoft/Workbench/WorkbenchCopyThread.html#method___construct", "name": "Padosoft\\Workbench\\WorkbenchCopyThread::__construct", "doc": "&quot;WorkbenchCopyThread constructor.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchCopyThread", "fromLink": "Padosoft/Workbench/WorkbenchCopyThread.html", "link": "Padosoft/Workbench/WorkbenchCopyThread.html#method_run", "name": "Padosoft\\Workbench\\WorkbenchCopyThread::run", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/WorkbenchDoc.html", "name": "Padosoft\\Workbench\\WorkbenchDoc", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchDoc", "fromLink": "Padosoft/Workbench/WorkbenchDoc.html", "link": "Padosoft/Workbench/WorkbenchDoc.html#method_handle", "name": "Padosoft\\Workbench\\WorkbenchDoc::handle", "doc": "&quot;Execute the console command.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchDoc", "fromLink": "Padosoft/Workbench/WorkbenchDoc.html", "link": "Padosoft/Workbench/WorkbenchDoc.html#method_setWorkbenchSettings", "name": "Padosoft\\Workbench\\WorkbenchDoc::setWorkbenchSettings", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchDoc", "fromLink": "Padosoft/Workbench/WorkbenchDoc.html", "link": "Padosoft/Workbench/WorkbenchDoc.html#method_getWorkbenchSettings", "name": "Padosoft\\Workbench\\WorkbenchDoc::getWorkbenchSettings", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchDoc", "fromLink": "Padosoft/Workbench/WorkbenchDoc.html", "link": "Padosoft/Workbench/WorkbenchDoc.html#method___get", "name": "Padosoft\\Workbench\\WorkbenchDoc::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchDoc", "fromLink": "Padosoft/Workbench/WorkbenchDoc.html", "link": "Padosoft/Workbench/WorkbenchDoc.html#method___set", "name": "Padosoft\\Workbench\\WorkbenchDoc::__set", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/WorkbenchServiceProvider.html", "name": "Padosoft\\Workbench\\WorkbenchServiceProvider", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchServiceProvider", "fromLink": "Padosoft/Workbench/WorkbenchServiceProvider.html", "link": "Padosoft/Workbench/WorkbenchServiceProvider.html#method_boot", "name": "Padosoft\\Workbench\\WorkbenchServiceProvider::boot", "doc": "&quot;Bootstrap the application events.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchServiceProvider", "fromLink": "Padosoft/Workbench/WorkbenchServiceProvider.html", "link": "Padosoft/Workbench/WorkbenchServiceProvider.html#method_register", "name": "Padosoft\\Workbench\\WorkbenchServiceProvider::register", "doc": "&quot;Register the service provider.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchServiceProvider", "fromLink": "Padosoft/Workbench/WorkbenchServiceProvider.html", "link": "Padosoft/Workbench/WorkbenchServiceProvider.html#method_provides", "name": "Padosoft\\Workbench\\WorkbenchServiceProvider::provides", "doc": "&quot;Get the services provided by the provider.&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/WorkbenchSettings.html", "name": "Padosoft\\Workbench\\WorkbenchSettings", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchSettings", "fromLink": "Padosoft/Workbench/WorkbenchSettings.html", "link": "Padosoft/Workbench/WorkbenchSettings.html#method___construct", "name": "Padosoft\\Workbench\\WorkbenchSettings::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchSettings", "fromLink": "Padosoft/Workbench/WorkbenchSettings.html", "link": "Padosoft/Workbench/WorkbenchSettings.html#method_prepare", "name": "Padosoft\\Workbench\\WorkbenchSettings::prepare", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchSettings", "fromLink": "Padosoft/Workbench/WorkbenchSettings.html", "link": "Padosoft/Workbench/WorkbenchSettings.html#method_setRequested", "name": "Padosoft\\Workbench\\WorkbenchSettings::setRequested", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchSettings", "fromLink": "Padosoft/Workbench/WorkbenchSettings.html", "link": "Padosoft/Workbench/WorkbenchSettings.html#method_getRequested", "name": "Padosoft\\Workbench\\WorkbenchSettings::getRequested", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchSettings", "fromLink": "Padosoft/Workbench/WorkbenchSettings.html", "link": "Padosoft/Workbench/WorkbenchSettings.html#method___get", "name": "Padosoft\\Workbench\\WorkbenchSettings::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchSettings", "fromLink": "Padosoft/Workbench/WorkbenchSettings.html", "link": "Padosoft/Workbench/WorkbenchSettings.html#method___set", "name": "Padosoft\\Workbench\\WorkbenchSettings::__set", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Padosoft\\Workbench", "fromLink": "Padosoft/Workbench.html", "link": "Padosoft/Workbench/WorkbenchVersion.html", "name": "Padosoft\\Workbench\\WorkbenchVersion", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_handle", "name": "Padosoft\\Workbench\\WorkbenchVersion::handle", "doc": "&quot;Execute the console command.&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_runSemVer", "name": "Padosoft\\Workbench\\WorkbenchVersion::runSemVer", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_semVerAnalisys", "name": "Padosoft\\Workbench\\WorkbenchVersion::semVerAnalisys", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_getActiveBranch", "name": "Padosoft\\Workbench\\WorkbenchVersion::getActiveBranch", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_getLastTagVersionArray", "name": "Padosoft\\Workbench\\WorkbenchVersion::getLastTagVersionArray", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_getLastTagVersion", "name": "Padosoft\\Workbench\\WorkbenchVersion::getLastTagVersion", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_createSemverCopyFolder", "name": "Padosoft\\Workbench\\WorkbenchVersion::createSemverCopyFolder", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_checkoutToTagVersion", "name": "Padosoft\\Workbench\\WorkbenchVersion::checkoutToTagVersion", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_addAndCommit", "name": "Padosoft\\Workbench\\WorkbenchVersion::addAndCommit", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_pushOriginActiveBranch", "name": "Padosoft\\Workbench\\WorkbenchVersion::pushOriginActiveBranch", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_tagActiveBranch", "name": "Padosoft\\Workbench\\WorkbenchVersion::tagActiveBranch", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_pushTagOriginActiveBranch", "name": "Padosoft\\Workbench\\WorkbenchVersion::pushTagOriginActiveBranch", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_pullOriginMaster", "name": "Padosoft\\Workbench\\WorkbenchVersion::pullOriginMaster", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_pullOriginActiveBranch", "name": "Padosoft\\Workbench\\WorkbenchVersion::pullOriginActiveBranch", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_formatColorRedText", "name": "Padosoft\\Workbench\\WorkbenchVersion::formatColorRedText", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_setWorkbenchSettings", "name": "Padosoft\\Workbench\\WorkbenchVersion::setWorkbenchSettings", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_getWorkbenchSettings", "name": "Padosoft\\Workbench\\WorkbenchVersion::getWorkbenchSettings", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method_validateTAG", "name": "Padosoft\\Workbench\\WorkbenchVersion::validateTAG", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method___get", "name": "Padosoft\\Workbench\\WorkbenchVersion::__get", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Padosoft\\Workbench\\WorkbenchVersion", "fromLink": "Padosoft/Workbench/WorkbenchVersion.html", "link": "Padosoft/Workbench/WorkbenchVersion.html#method___set", "name": "Padosoft\\Workbench\\WorkbenchVersion::__set", "doc": "&quot;&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


