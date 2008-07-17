-----------------------------------------
 The "External Audio" Module for FreePBX
-----------------------------------------

This module provides a public address interface for paging


Operation
---------

This module provides a destination that can be selected from another module such as the miscellaneous application module. This destination connects to the audio line interfaces on the PBX hardware so allowing paging over a PA system. 

The extaudio module also controls the audio mixer to set volume levels for interfacing to a public address system.

Normally an external audio input (such as from a radio) is passed through to the external audio output (the PA system). If there is a call to the extaudio destination then the audio input is muted and instead the caller's voice is output to the PA system. At the end of the call the normal audio (such as from a radio) is resumed. 


Preconditions
-------------

This module expects the alsa mixer to exist on the local system with "Line", "PCM" and "Capture" audio interfaces
This module requires one of the asterisk console modules to be loaded - either chan_oss.so or preferably chan_alsa.so

Open Issues
-----------

This module is not compatible with some implementations of live/streaming music on hold (i.e. those implementations that cannot coexist with chan_oss.so/chan_alsa.so)


Author
------
nick.lewis@atltelecom.com